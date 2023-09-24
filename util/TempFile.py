import os
import pathlib
import shutil


class TempFile:
    def __init__(self, path):
        self._path = pathlib.Path(path).absolute()

    def __enter__(self):
        return self._path

    def delete_file(self):
        if os.path.exists(self._path):
            os.remove(self._path)

    def __exit__(self, exc_type, exc_val, exc_tb):
        self.delete_file()
