from abc import ABC, abstractmethod
from multiprocessing import Queue
from multiprocessing.managers import DictProxy
from multiprocessing.sharedctypes import Synchronized

from PIL import Image, ImageChops, ImageStat


class Processor(ABC):
    def __init__(
        self, tasks_queue: Queue, processed_frames: DictProxy, pause_flag: Synchronized
    ) -> None:
        self.tasks_queue = tasks_queue
        self.processed_frames = processed_frames
        self.pause_flag = pause_flag

    @abstractmethod
    def process(self):
        raise NotImplementedError

    @staticmethod
    def get_image_diff(image0: Image.Image, image1: Image.Image) -> float:
        """
        get the percentage difference between two images

        :param image0 Image.Image: the image to compare
        :param image1 Image.Image: the image to compare against
        :rtype float: precentage difference between two frames
        """
        difference_stat = ImageStat.Stat(ImageChops.difference(image0, image1))
        return sum(difference_stat.mean) / (len(difference_stat.mean) * 255) * 100

    """
    def run(
        self,
    ) -> None:
        self.running = True
        while self.running is True:
            self.process()
        self.running = False
        return super().run()

    def stop(self, _signal_number, _frame) -> None:
        self.running = False
    """
