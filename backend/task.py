from time import sleep

from celery import Celery
from tqdm import tqdm

app = Celery('tasks', broker='pyamqp://guest@localhost//')


@app.task
def add(x, y):
    for _ in tqdm(range(10)):
        sleep(1)
    return x + y
