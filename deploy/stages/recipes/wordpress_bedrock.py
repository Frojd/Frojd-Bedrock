"""
recipes.wordpress_bedrock
--------------------------
Recipe for dealing with bedrock based wordpress installations
"""

from fabrik import paths, hooks
from fabric.state import env
from fabric.context_managers import lcd
from fabric.operations import local


def init_tasks():
    with lcd(".."):
        local("curl -sS https://getcomposer.org/installer | php")
        local("php composer.phar install --no-scripts --optimize-autoloader --no-dev")
        local("php composer.phar build")
        local("rm composer.phar")


def setup():
    env.run("touch %s" % paths.get_shared_path(".env"))
    env.run("touch %s" % paths.get_shared_path("robots.txt"))


def deploy():
    paths.symlink(
        paths.get_shared_path(".env"),
        paths.get_current_release_path(".env")
    )


def after_deploy():
    env.run("rm -rf %s" % paths.get_current_path("app/uploads"))

    paths.symlink(
        paths.get_upload_path(),
        paths.get_current_path("app/uploads")
    )

    paths.symlink(
        paths.get_shared_path("robots.txt"),
        paths.get_current_path("robots.txt")
    )

    env.run('rm -rf /var/run/nginx-cache/*')
    env.run('service nginx restart')
    env.run('service php7.0-fpm restart')

    env.run(
        "cd %s && "
        "wp --allow-root cache flush && "
        "wp --allow-root rewrite flush" % paths.get_current_path()
    )


def register():
    hooks.register_hook("init_tasks", init_tasks)
    hooks.register_hook("setup", setup)
    hooks.register_hook("deploy", deploy)
    hooks.register_hook("after_deploy", after_deploy)


def unregister():
    hooks.unregister_hook("init_tasks", init_tasks)
    hooks.unregister_hook("setup", setup)
    hooks.unregister_hook("deploy", after_deploy)
    hooks.unregister_hook("after_deploy", after_deploy)
