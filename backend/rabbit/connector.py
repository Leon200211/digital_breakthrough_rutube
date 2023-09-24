import os
import time

from typing import *

import pika
from dotenv import dotenv_values
from pika.connection import Connection

from .. import logger




def create_queue(channel, queue_name):
    channel.queue_declare(
        queue=queue_name,
        durable=True,
        exclusive=False,  # если очередь уже существует,
        auto_delete=False,
        arguments={'x-max-priority': 255, 'x-queue-type=classic': 'classic'}
    )
    logger.info(f'Queue {queue_name} has been added')


class Connector:
    def __init__(self, env_path=None) -> None:
        self.port = None
        self._connection: Connection | None = None
        self.virtual_host = None
        self.output_queue = None
        self.input_queue = None
        self.username = None
        self.password = None
        self.ip = None
        self.url = None
        if env_path:
            self.load_env_from_file(env_path)
        self.load_env_from_os()

    def load_env_from_file(self, env_path):
        d = dict(dotenv_values(env_path))
        self.url = d.get('RABBIT_URL')
        self.input_queue = d.get('BACKEND_INPUT_QUEUE')
        self.output_queue = d.get('BACKEND_OUTPUT_QUEUE')

    def load_env_from_os(self):
        # logger.info(dict(os.environ))
        # print(dict(os.environ))
        self.url = os.environ.get('RABBIT_URL')
        protocol = self.url.split('//')[0].rstrip(':')
        without_protocol = self.url.split('//')[-1]
        if '@' in self.url:
            before_host = without_protocol.split('@')[0]
            self.username = before_host.split(':')[0]
            if len(before_host.split(':')) > 1:
                self.password = before_host.split(':')[1]
        after_username = without_protocol.split('@')[-1]
        host_with_port = after_username.split('/')[0]
        self.ip = host_with_port.split(':')[0]
        if len(host_with_port.split(':')) > 1:
            self.port = host_with_port.split(':')[1]
        self.virtual_host =  ''
        if len(after_username.split('/')) > 1:
            self.virtual_host = after_username.split('/')[1]

        self.input_queue = os.environ.get('BACKEND_INPUT_QUEUE')
        self.output_queue = os.environ.get('BACKEND_OUTPUT_QUEUE')

        if len(self.virtual_host) == 0:
            self.virtual_host = '/'

        logger.info('Envs are loaded')

    def __enter__(self):
        """
        :return: connection, channel, input_queue, output_queue
        """
        return self._connect()

    def __exit__(self, exc_type, exc_val, exc_tb):
        if self._connection.is_open:
            self._connection.close()

    def _connect(self) -> Any:
        tries = 0
        while True:
            try:
                tries += 1
                logger.info(f'Trying to connect at {tries} time')
                connection = pika.BlockingConnection(
                    [
                        pika.ConnectionParameters(
                            host=self.ip,
                            port=self.port,
                            virtual_host=self.virtual_host,
                            credentials=pika.PlainCredentials(
                                username=self.username if self.username is not None
                                else pika.ConnectionParameters.DEFAULT_USERNAME,
                                password=self.password if self.password is not None
                                else pika.ConnectionParameters.DEFAULT_PASSWORD
                            ),
                        )
                    ],

                )
                self._connection = connection
                channel = connection.channel()
                logger.info('Connection successful')

                create_queue(channel, self.input_queue)
                create_queue(channel, self.output_queue)

                return connection, channel, self.input_queue, self.output_queue
            except Exception as _:
                logger.info(f'Connection failed. Waiting for a 5 seconds...')
                time.sleep(5)
