import json
import os
import shutil
import subprocess
from pathlib import Path
from src import logger
from src.video2x import Video2X
from src.rabbit.connector import Connector
from src.rabbit.consumer import RabbitConsumer


def inference(video_path):
    print(video_path)
    video2x = Video2X()
    
    tmp_folder = Path(os.getcwd()) / 'storage' / 'tmp'
    upscale_folder = tmp_folder / 'upscale'
    if upscale_folder.exists() is False:
        upscale_folder.mkdir()
    
    input_path = tmp_folder / Path(video_path).name
    upscaled_path = upscale_folder / input_path.name
    
    input_path = str(input_path)
    upscaled_path = str(upscaled_path)
    
    model_name = 'model_tensorrt'
    
    logger.info([input_path, upscaled_path])
    logger.info([input_path])
    
    shutil.copy(input_path, upscaled_path)
    
    video2x.upscale(
        model_name,
        input_path,
        upscaled_path,
        856,
        480,
        3,
        2,
        0,
        ''
    )
    return upscaled_path


if __name__ == '__main__':
    con = Connector()

    connection, channel, input_queue, output_queue = con.connect()

    cons = RabbitConsumer( 
        rabbit_channel=channel,
        rabbit_connection=connection,
        rabbit_input_queue=input_queue,
        rabbit_output_queue=output_queue,
        pipeline=inference
    )
    cons.listen()
