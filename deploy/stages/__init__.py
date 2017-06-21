from fabric.state import env

from demo import demo  # NOQA
from stage import stage  # NOQA
from prod import prod  # NOQA


env.scp_ignore_list = [
    'deploy',
    'docker',
    'git-hooks',
]
