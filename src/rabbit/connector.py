import os
import amqp
import time

from .. import logger
from typing import *
from dotenv import dotenv_values

class Connector:
    def __init__(self, env_path=None) -> None:
        if env_path:
            self.load_env_from_file(env_path)
        self.load_env_from_os()

    def load_env_from_file(self, env_path):
        d = dict(dotenv_values(env_path))
        os.environ['RABBIT_URL'] = d.get('RABBIT_URL')
        os.environ['INPUT_QUEUE'] = d.get('INPUT_QUEUE')
        os.environ['OUTPUT_QUEUE'] = d.get('OUTPUT_QUEUE')
    
    def load_env_from_os(self):
        self.url = os.environ.get('RABBIT_URL')
        self.host, self.virtual_host = self.url.split('@')[1].split('/')
        _, self.username, self.password = self.url.split('@')[0].split(':')
        self.username = self.username.replace('//', '')
        
        self.input_queue = os.environ.get('INPUT_QUEUE') 
        self.output_queue = os.environ.get('OUTPUT_QUEUE')

        if len(self.virtual_host) == 0:
            self.virtual_host = '/'

        logger.info('Envs are loaded')
    
    def create_queue(self, channel, queue_name):
        channel.queue_declare(
            queue=queue_name,
            durable=True,
            exclusive=False,  # если очередь уже существует,
            auto_delete=False,
            arguments={'x-max-priority': 255, 'x-queue-type=classic': 'classic'}
        )
        logger.info(f'Queue {queue_name} has been added')
    
    def connect(self) -> Any:
        tries = 0
        while True:
            try:
                tries += 1
                logger.info(f'Trying to connect at {tries} time')
                connection = amqp.Connection(
                    host=self.host,
                    userid=self.username,
                    password=self.password,
                    virtual_host=self.virtual_host
                )

                connection.connect()
                channel = connection.channel()
                logger.info('Connection successful')

                self.create_queue(channel,self.input_queue)
                self.create_queue(channel,self.output_queue)
                
                return connection, channel, self.input_queue, self.output_queue
            except Exception as e:
                logger.info(f'Connection failed. Waiting for a 5 seconds...')
                time.sleep(5)
