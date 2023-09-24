import os
import sys
import threading
import time
from typing import IO


class PipePrinter(threading.Thread):
    def __init__(self, stderr: IO[bytes]) -> None:
        threading.Thread.__init__(self)
        self.stderr = stderr
        self.running = False

        # set read mode to non-blocking
        os.set_blocking(self.stderr.fileno(), False)

    def _print_output(self) -> None:
        output = self.stderr.read()
        if output is not None and len(output) != 0:
            print(output.decode(), file=sys.stderr)

    def run(self) -> None:
        self.running = True

        # keep printing contents in the PIPE
        while self.running is True:
            time.sleep(0.5)

            try:
                self._print_output()

            # pipe closed
            except ValueError:
                break

        return super().run()

    def stop(self) -> None:
        self.running = False
