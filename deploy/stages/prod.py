"""
Prod environment
"""

from fabric.state import env
from fabric.decorators import task

from fabrik.utils import get_stage_var


@task
def prod():
    # Recipe
    from recipes import wordpress_bedrock
    wordpress_bedrock.register()

    # SSH Config
    env.hosts = [get_stage_var("HOST")]
    env.user = get_stage_var("USER")
    env.password = get_stage_var("PASSWORD", "")
    env.key_filename = get_stage_var("KEY_FILENAME")
    env.forward_agent = True

    env.app_path = get_stage_var("APP_PATH")
    env.source_path = get_stage_var("APP_SOURCE_PATH", "src")
    env.public_path = get_stage_var("PUBLIC_PATH")

    # Other
    env.max_releases = 5
