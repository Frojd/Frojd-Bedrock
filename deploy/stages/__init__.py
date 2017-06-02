from fabric.state import env

from local import local  # NOQA
from demo import demo  # NOQA
from stage import stage  # NOQA
from prod import prod  # NOQA

env.repro_url = "git@github.com:Frojd/Frojd-Bedrock.git"