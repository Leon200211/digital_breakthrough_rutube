import argparse
import os
import pathlib
import sys

from loguru import logger
from rich import print as rich_print

from . import __version__
from .video2x import LOGURU_FORMAT, Video2X

# algorithms available for upscaling tasks
UPSCALING_ALGORITHMS = ["waifu2x", "srmd", "realsr", "realcugan", "anime4k"]

# algorithms available for frame interpolation tasks
INTERPOLATION_ALGORITHMS = ["rife"]


def parse_arguments() -> argparse.Namespace:
    """
    parse command line arguments

    :rtype argparse.Namespace: command parsing results
    """
    parser = argparse.ArgumentParser(
        prog="video2x",
        formatter_class=argparse.ArgumentDefaultsHelpFormatter,
    )
    parser.add_argument(
        "--version", help="show version information and exit", action="store_true"
    )
    parser.add_argument(
        "-i",
        "--input",
        type=pathlib.Path,
        help="input file/directory path",
        required=True,
    )
    parser.add_argument(
        "-o",
        "--output",
        type=pathlib.Path,
        help="output file/directory path",
        required=True,
    )
    parser.add_argument(
        "-p", "--processes", type=int, help="number of processes to launch", default=1
    )
    parser.add_argument(
        "-l",
        "--loglevel",
        choices=["trace", "debug", "info", "success", "warning", "error", "critical"],
        default="info",
    )

    # upscaler arguments
    action = parser.add_subparsers(
        help="action to perform", dest="action", required=True
    )

    upscale = action.add_parser(
        "upscale",
        help="upscale a file",
        formatter_class=argparse.ArgumentDefaultsHelpFormatter,
        add_help=False,
    )
    upscale.add_argument(
        "--help", action="help", help="show this help message and exit"
    )
    upscale.add_argument("-w", "--width", type=int, help="output width")
    upscale.add_argument("-h", "--height", type=int, help="output height")
    upscale.add_argument("-n", "--noise", type=int, help="denoise level", default=3)
    upscale.add_argument(
        "-a",
        "--algorithm",
        choices=UPSCALING_ALGORITHMS,
        help="algorithm to use for upscaling",
        default=UPSCALING_ALGORITHMS[0],
    )
    upscale.add_argument(
        "-t",
        "--threshold",
        type=float,
        help=(
            "skip if the percent difference between two adjacent frames is below this"
            " value; set to 0 to process all frames"
        ),
        default=0,
    )

    # interpolator arguments
    interpolate = action.add_parser(
        "interpolate",
        help="interpolate frames for file",
        formatter_class=argparse.ArgumentDefaultsHelpFormatter,
        add_help=False,
    )
    interpolate.add_argument(
        "--help", action="help", help="show this help message and exit"
    )
    interpolate.add_argument(
        "-a",
        "--algorithm",
        choices=INTERPOLATION_ALGORITHMS,
        help="algorithm to use for upscaling",
        default=INTERPOLATION_ALGORITHMS[0],
    )
    interpolate.add_argument(
        "-t",
        "--threshold",
        type=float,
        help=(
            "skip if the percent difference between two adjacent frames exceeds this"
            " value; set to 100 to interpolate all frames"
        ),
        default=5,
    )

    return parser.parse_args()


def main() -> int:
    """
    command line entrypoint for direct CLI invocation

    :rtype int: 0 if completed successfully, else other int
    """

    try:
        # display version and lawful informaition
        if "--version" in sys.argv:
            rich_print(LEGAL_INFO)
            return 0

        # parse command line arguments
        args = parse_arguments()

        # check input/output file paths
        if not args.input.exists():
            logger.critical(f"Cannot find input file: {args.input}")
            return 1
        if not args.input.is_file():
            logger.critical("Input path is not a file")
            return 1
        if not args.output.parent.exists():
            logger.critical(f"Output directory does not exist: {args.output.parent}")
            return 1

        # set logger level
        if os.environ.get("LOGURU_LEVEL") is None:
            os.environ["LOGURU_LEVEL"] = args.loglevel.upper()

        # remove default handler
        logger.remove()

        # add new sink with custom handler
        logger.add(sys.stderr, colorize=True, format=LOGURU_FORMAT)

        # print package version and copyright notice
        logger.opt(colors=True).info(f"<magenta>Video2X {__version__}</magenta>")
        logger.opt(colors=True).info(
            "<magenta>Copyright (C) 2018-2023 K4YT3X and contributors.</magenta>"
        )

        # initialize video2x object
        video2x = Video2X()

        if args.action == "upscale":
            print(args)
            video2x.upscale(
                args.input,
                args.output,
                args.width,
                args.height,
                args.noise,
                args.processes,
                args.threshold,
                args.algorithm,
            )

        elif args.action == "interpolate":
            video2x.interpolate(
                args.input,
                args.output,
                args.processes,
                args.threshold,
                args.algorithm,
            )

    # don't print the traceback for manual terminations
    except KeyboardInterrupt:
        return 2

    except Exception as error:
        logger.exception(error)
        return 1

    # if no exceptions were produced
    else:
        logger.success("Processing completed successfully")
        return 0


if __name__ == "__main__":
    sys.exit(main())
