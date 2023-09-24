import json
from threading import Thread, Lock, Event

import pika
from amqp import Message
from pika.adapters.blocking_connection import BlockingChannel
from pika.connection import Connection

from backend import logger
from backend.rabbit.connector import Connector
from backend.rabbit.publisher import process_file


class VideoConsumerThread(Thread):
    def __init__(self, check_lock: Lock, callback_map: dict[int, dict], new_item_event: Event):
        super().__init__()
        self._check_lock = check_lock
        self._callback_map = callback_map
        self._new_item_event = new_item_event

    def run(self) -> None:
        con = Connector(
            env_path='configs/rabbit.env'
        )
        # connection, channel, input_queue, output_queue
        while True:
            try:
                with con as (connection, channel, input_queue, output_queue):
                    connection: Connection
                    channel: BlockingChannel
                    while True:
                            no_items = False
                            with self._check_lock:
                                if len(self._callback_map) <= 0:
                                    no_items = True
                            channel.basic_qos(prefetch_count=1)
                            x = channel.basic_get(con.input_queue)
                            if x != (None, None, None):
                                channel.basic_ack(x[0].delivery_tag)
                                self.callback(*x)
                            if no_items:
                                logger.info("NO ITEMS")
                                self._new_item_event.wait()
                                self._new_item_event.clear()
                                logger.info("UNSET")
            except Exception as e:
                logger.error(e)
                logger.info("RECONNECT")

    def callback(self, ch, method, body):
        logger.info("CALLBACK")
        logger.info(ch)
        logger.info(method)
        logger.info(body)
        body_str = body.decode('utf-8')
        body_json = json.loads(body_str)

        upload_id = int(body_json['upload_id'])
        failed = False
        with self._check_lock:
            if upload_id not in self._callback_map:
                return
            callback_json = self._callback_map.pop(upload_id)
        callback = callback_json['callback']
        input_file = callback_json['path']
        try:
            process_status = str(body_json['status'])
            if process_status.lower() == 'success':
                ready_file_path = body_json['path']
                callback(ready_file_path)
            else:
                failed = True
        except Exception as e:
            logger.error(e)
            failed = True
        finally:
            if upload_id in self._callback_map:
                self._callback_map.pop(upload_id)
        if failed:
            logger.info("FAILED. RETRY")
            with self._check_lock:
                self._callback_map[upload_id] = callback_json
            process_file(input_file, upload_id)





def main():
    lock = Lock()
    m = {1: lambda: print("Hi")}
    e = Event()
    VideoConsumerThread(lock, m, e).start()
    e.set()
    print("SET")


if __name__ == '__main__':
    main()
