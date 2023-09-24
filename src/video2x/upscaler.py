import math
import time


from PIL import Image
from .processor import Processor

import numpy as np
import torchvision.transforms as T
import tritonclient.grpc as grpcclient
from PIL import Image


def test_infer(
    triton_client,
    model_name,
    input0_data,
):
    inputs = []
    outputs = []
    inputs.append(grpcclient.InferInput("input", [*input0_data.shape], "FP16"))

    # Initialize the data
    inputs[0].set_data_from_numpy(input0_data)

    outputs.append(grpcclient.InferRequestedOutput("output"))
    query_params = {"test_1": "1", "test_2": "2"}
    results = triton_client.infer(
        model_name,
        inputs,
        outputs=outputs
    )

    return results


class Upscaler:
    # fixed scaling ratios supported by the algorithms
    # that only support certain fixed scale ratios
    ALGORITHM_FIXED_SCALING_RATIOS = {
        "anime4k": [-1],
        "realcugan": [1, 2, 3, 4],
        "realsr": [4],
        "srmd": [2, 3, 4],
        "waifu2x": [1, 2],
    }

    ALGORITHM_CLASSES = {
        # "anime4k": Anime4K,
        # "realcugan": Realcugan,
        # "realsr": Realsr,
        # "srmd": Srmd,
        # "waifu2x": Waifu2x,
    }

    processor_objects = {}
    
    triton_client = grpcclient.InferenceServerClient(url="tritonserver:8001", verbose=False)

    @staticmethod
    def _get_scaling_tasks(
        input_width: int,
        input_height: int,
        output_width: int,
        output_height: int,
        algorithm: str,
    ) -> list:
        """
        Get the required tasks for upscaling the image until it is larger than
        or equal to the desired output dimensions. For example, SRMD only supports
        2x, 3x, and 4x, so upsclaing an image from 320x240 to 3840x2160 will
        require the SRMD to run 3x then 4x. In this case, this function will
        return [3, 4].

        :param input_width int: input image width
        :param input_height int: input image height
        :param output_width int: desired output image width
        :param output_height int: desired output image size
        :param algorithm str: upsclaing algorithm
        :rtype list: the list of upsclaing tasks required
        """
        # calculate required minimum scale ratio
        output_scale = max(output_width / input_width, output_height / input_height)

        # select the optimal algorithm scaling ratio to use
        supported_scaling_ratios = sorted(
            Upscaler.ALGORITHM_FIXED_SCALING_RATIOS[algorithm]
        )

        remaining_scaling_ratio = math.ceil(output_scale)

        # if the scaling ratio is 1.0
        # apply the smallest scaling ratio available
        if remaining_scaling_ratio == 1:
            return [supported_scaling_ratios[0]]

        # if the processor supports arbitrary scales
        # return only one job
        if supported_scaling_ratios[0] == -1:
            return [remaining_scaling_ratio]

        scaling_jobs = []
        while remaining_scaling_ratio > 1:
            for ratio in supported_scaling_ratios:
                if ratio >= remaining_scaling_ratio:
                    scaling_jobs.append(ratio)
                    remaining_scaling_ratio /= ratio
                    break

            else:
                found = False
                for i in supported_scaling_ratios:
                    for j in supported_scaling_ratios:
                        if i * j >= remaining_scaling_ratio:
                            scaling_jobs.extend([i, j])
                            remaining_scaling_ratio /= i * j
                            found = True
                            break
                    if found is True:
                        break

                if found is False:
                    scaling_jobs.append(supported_scaling_ratios[-1])
                    remaining_scaling_ratio /= supported_scaling_ratios[-1]
        return scaling_jobs

    def upscale_image(
        self,
        model_name,
        image: Image.Image,
        output_width: int,
        output_height: int,
        algorithm: str,
        noise: int,
    ) -> Image.Image:
        """
        upscale an image

        :param image Image.Image: the image to upscale
        :param output_width int: the desired output width
        :param output_height int: the desired output height
        :param algorithm str: the algorithm to use
        :param noise int: the noise level (available only for some algorithms)
        :rtype Image.Image: the upscaled image
        """
        width, height = image.size
        

        # process the image with the selected algorithm
        image = T.Compose([T.ToTensor()])(image).unsqueeze(0).numpy().astype(np.float16)
        # print(image.shape)
        image = test_infer(self.triton_client, model_name, image)
        image = image.as_numpy("output").astype(np.float32)
        image = np.where(image > 1, 1, image)
        image = np.where(image < 0, 0, image)
        import torch
        image = T.ToPILImage()(torch.tensor(image[0]))
        

        # downscale the image to the desired output size and
        # save the image to disk
        return image.resize((output_width, output_height), Image.Resampling.LANCZOS)


class UpscalerProcessor(Processor, Upscaler):
    def process(self) -> None:
        task = self.tasks_queue.get()
        while task is not None:
            try:
                if self.pause_flag.value is True:
                    time.sleep(0.1)
                    continue

                # unpack the task's values
                (
                    frame_index,
                    previous_frame,
                    current_frame,
                    (model_name, output_width, output_height, algorithm, noise, threshold),
                ) = task

                # calculate the %diff between the current frame and the previous frame
                difference_ratio = 0
                if previous_frame is not None:
                    difference_ratio = self.get_image_diff(
                        previous_frame, current_frame
                    )

                # if the difference is lower than threshold, skip this frame
                if difference_ratio < threshold:
                    # make the current image the same as the previous result
                    self.processed_frames[frame_index] = True

                # if the difference is greater than threshold
                # process this frame
                else:
                    self.processed_frames[frame_index] = self.upscale_image(
                        model_name, current_frame, output_width, output_height, algorithm, noise
                    )

                task = self.tasks_queue.get()

            except KeyboardInterrupt:
                break
