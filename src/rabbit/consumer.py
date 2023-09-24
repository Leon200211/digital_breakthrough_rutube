import json
import time
from pathlib import Path

import amqp
import traceback

from .. import logger
from typing import *


class RabbitConsumer:
    class RabbitAnswer:
        def __init__(self, path: str, upload_id: int, success: bool):
            self.path = path
            self.upload_id = upload_id
            self.success = success

        @property
        def json(self):
            return json.dumps({'path': self.path, 'upload_id': self.upload_id,
                               'status': 'success' if self.success else 'fail'},
                              ensure_ascii=False)

    def __init__(
            self,
            rabbit_channel: amqp.Channel,
            rabbit_connection: amqp.Connection,
            rabbit_input_queue: str,
            rabbit_output_queue: str,
            pipeline: Callable
    ):

        assert rabbit_channel.is_open, 'Failed connection to RabbitMQ'

        self.rabbit_connection = rabbit_connection
        self.rabbit_channel = rabbit_channel

        self.rabbit_input_queue = rabbit_input_queue
        self.rabbit_output_queue = rabbit_output_queue

        self.pipeline = pipeline
        self.answer: RabbitConsumer.RabbitAnswer | None = None
        self.video_id: int | None = None

        logger.info(f'Consumer gets pipeline: {pipeline.__name__}')

    def _process_video(self, filepath) -> None:
        try:
            logger.info('Start processing a video')
            start_time = time.time()
            full_path = self.pipeline(filepath)
            self.path = str(Path(filepath).parent / 'upscale' / Path(full_path).name)
            batch_process_time = time.time() - start_time
            logger.info(f'Video has been processed in {batch_process_time}s')
            self._create_answer()
        except Exception as e:
            self.path = None
            self._create_answer()
            logger.error(e)

    def _create_answer(self) -> None:
        self.answer = self.RabbitAnswer(self.path, self.video_id, self.path is not None)

    def _publish_answer(self) -> None:
        body = self.answer.json
        msg = amqp.basic_message.Message(body=body)
        self.rabbit_channel.basic_publish(msg, exchange='', routing_key=self.rabbit_output_queue)
        logger.info(f'Send answer to {self.rabbit_output_queue}')

    def _listen_video(self) -> None:
        while True:
            message = self.rabbit_channel.basic_get(queue=self.rabbit_input_queue)
            if message:
                self.rabbit_channel.basic_ack(delivery_tag=message.delivery_tag)
                payload = json.loads(message.body)
                self.video_id = int(payload['upload_id'])
                self._process_video(payload['path'])
                self._publish_answer()
            else:
                break

    def listen(self, mode='run') -> None:
        self.rabbit_channel.basic_qos(prefetch_size=0,
                                      prefetch_count=1,
                                      a_global=False)
        logger.info(f'Start consuming on {self.rabbit_input_queue}')
        while True:
            try:
                self._listen_video()
            except:
                logger.error(f'{traceback.format_exc()}')
