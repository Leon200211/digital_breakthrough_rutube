import os
import pathlib
import shutil


class TempFolder:
    def __init__(self, path):
        self._path = pathlib.Path(path).absolute()

    def __enter__(self):
        self.make_folder()
        return self._path

    def delete_folder(self):
        shutil.rmtree(self._path, ignore_errors=True)

    def make_folder(self):
        if not self._path.exists():
            os.mkdir(self._path)

    def __exit__(self, exc_type, exc_val, exc_tb):
        self.delete_folder()