import time

from loguru import logger
from PIL import ImageChops, ImageStat
# from rife_ncnn_vulkan_python.rife_ncnn_vulkan import Rife

from .processor import Processor


class Interpolator:
    # ALGORITHM_CLASSES = {"rife": Rife}

    processor_objects = {}

    def interpolate_image(self, image0, image1, difference_threshold, algorithm):
        difference = ImageChops.difference(image0, image1)
        difference_stat = ImageStat.Stat(difference)
        difference_ratio = (
            sum(difference_stat.mean) / (len(difference_stat.mean) * 255) * 100
        )

        if difference_ratio < difference_threshold:
            processor_object = self.processor_objects.get(algorithm)
            if processor_object is None:
                processor_object = self.ALGORITHM_CLASSES[algorithm](0)
                self.processor_objects[algorithm] = processor_object
            interpolated_image = processor_object.process(image0, image1)

        else:
            interpolated_image = image0

        return interpolated_image


class InterpolatorProcessor(Processor, Interpolator):
    def process(self) -> None:
        task = self.tasks_queue.get()
        while task is not None:
            try:
                if self.pause_flag.value is True:
                    time.sleep(0.1)
                    continue

                (
                    frame_index,
                    image0,
                    image1,
                    (difference_threshold, algorithm),
                ) = task

                if image0 is None:
                    task = self.tasks_queue.get()
                    continue

                interpolated_image = self.interpolate_image(
                    image0, image1, difference_threshold, algorithm
                )

                if frame_index == 1:
                    self.processed_frames[0] = image0
                self.processed_frames[frame_index * 2 - 1] = interpolated_image
                self.processed_frames[frame_index * 2] = image1

                task = self.tasks_queue.get()

            except (SystemExit, KeyboardInterrupt):
                break

            except Exception as error:
                logger.exception(error)
                break
