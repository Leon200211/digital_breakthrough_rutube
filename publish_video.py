from pathlib import Path

from src.rabbit.connector import Connector
from src.rabbit.publisher import RabbitPublisher

import os
import argparse

parser = argparse.ArgumentParser(description='Videos to RabbitMQ')
parser.add_argument('input', default='videos', type=str, help='Input dir/file relative !!!Storage!!! folder')

if __name__ == '__main__':
    con = Connector(
        env_path='configs/rabbit.env'
    )
    connection, channel, input_queue, output_queue = con.connect()
    
    pub = RabbitPublisher(
        channel,
        connection,
        input_queue
    )
    
    args = parser.parse_args()
    if os.path.isdir(f'./storage/{args.input}'):
        folder = Path(f'./storage/{args.input}')
        files = list(folder.rglob('*.mp4'))
        for vid in files:
            vid_path = '/'.join(files[0].parts[1:])
            pub.publish({'path':(Path('/adapter_triton/storage') / vid_path).as_posix()})
    else:
        pub.publish({'path':(Path('/adapter_triton/storage') / args.input).as_posix()})