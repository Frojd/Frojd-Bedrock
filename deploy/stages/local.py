"""
Local environment
"""

import os

from fabric.state import env
from fabrik.hooks import hook
from fabric.decorators import task
from fabric.context_managers import lcd

from fabrik import paths
from fabrik.utils.elocal import elocal
from fabrik.utils import get_stage_var
from fabrik.ext import composer


@task
def local():
    # Recipe
    from fabrik.recipes import wordpress  # NOQA

    # Local versions of run/cd/exists
    env.run = elocal
    env.cd = lcd
    env.exists = os.path.exists

    # Metadata
    env.stage = "local"

    # VC
    env.branch = "develop"

    # SSH Config
    env.app_path = get_stage_var("APP_PATH")
    env.source_path = get_stage_var("APP_SOURCE_PATH", "src")
