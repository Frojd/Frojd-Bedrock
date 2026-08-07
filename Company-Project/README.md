# Company-Project

A short description of the project.

## Requirements

* [Docker](https://www.docker.com/) or [Valet](https://laravel.com/docs/8.x/valet)
* [Git flow](https://github.com/petervanderdoes/gitflow-avh)

## Installation (Using Docker)

> Creating a brand-new project from the boilerplate? Do
> [Setup project first time](#setup-project-first-time) first, then come back here.

This is how a developer gets an **existing** project running on their machine.

1. Make sure you have Docker installed
2. Add this ip to your hosts-file

    ```
    127.0.0.1 example.com.test
    ```

    On windows you can run this command to append it:

    ```
    echo 127.0.0.1 example.com.test >> c:\windows\System32\drivers\etc\hosts
    ```

3. Get the site running. In the root folder, run:

    ```
    make start
    ```

    The first time on your machine this sets up the .env-files, starts
    everything, installs the dependencies and copies the site content from a
    server. After that, `make start` just starts the site again. When it
    finishes it prints the site URL and the admin login.

4. Visit your site on: [http://example.com.test:8080](http://example.com.test:8080)

Other commands (run `make` to see them all):

* `make start` — start the site (sets it up the first time).
* `make resync stage=prod` — copy the latest content from a server again.
* `make bootstrap` — create a clean, empty site instead, for a brand-new
  project or when you can't copy an existing database.
* `make setup` — prepare git and git hooks when creating a brand-new project.

## Installation (Using Valet)

1. Make sure you have [Valet](https://laravel.com/docs/8.x/valet) installed ([How to install laravel valet on mac](https://medium.com/modulr/how-to-install-laravel-valet-on-mac-f061ce2d095e))

2. In root folder, run:
    ```
    make setup
    ```

    This will configure the docker setup as well as creating a local ROOT_FOLDER/.env which is the configuration file valet will use. Docker will keep running docker/config/web.env

3. Set up/configure a database

   Either create a local database and reconfigure ROOT_FOLDER/.env accordingly, or you could use docker:
   ```
   # Run in forground
   docker compose up db

   # Run in background
   docker compose up -d db
   ```

   If you are using docker, the DB_HOST should be set to "$LOCALIPADDRESS:$DOCKERPORT", this is configured by default by `make setup`.

   Although default, this might be useful if migrating older projects:

       - To retrieve your local ip you can use `ipconfig getifaddr en0` on a mac

       - To retrieve your docker db-port, check the ports config of the db-container in docker-compose.yml

4. Install composer dependencies

   By running `composer install`, if versions are conflicting or if you do not have composer installed
   it is also possible to run supplied docker container: `docker compose run composer install`

5. Create a valet link in the src-folder:
   ```
   cd src
   valet link example.com
   ```

6. Visit your site on `example.com.test`

7. Set up git-hooks
   ```
   chmod +x $PWD/git-hooks/bump-version.sh
   ln -nfs $PWD/git-hooks/bump-version.sh .git/hooks/post-flow-release-start
   ln -nfs $PWD/git-hooks/bump-version.sh .git/hooks/post-flow-hotfix-start
   ```
   
8. (optional) Set up SSL
   ```
   cd src
   valet secure
   ```
   You will also need to edit ROOT_FOLDER/.env WP_HOME and WP_SITEURL to use https for it to actually be utilized

   NOTE: This certificate will still issue a security warning since its self-signed

### Enable SSL 

If you need ssl for local development this can be enabled using mkcert with the following command: 

```
$ scripts/enable_ssl.sh
```

Please note that this installs a trusted development certificate on your local machine whcih gives complete power 
to intercept secure requests from your machine. Do not share it!


### Remote debugging for xdebug

Xdebug 3 is installed in the php-fpm image and configured through the
`XDEBUG_MODE` and `XDEBUG_CONFIG` environment variables in `docker-compose.yml`.
By default it connects back to `host.docker.internal` and starts with each
request, so step-debugging works out of the box once your IDE is listening on
the default port (9003) with the `PHPSTORM` server/IDE key.

To enable step-debugging, set the mode to `debug` for the `php-fpm` service in
`docker-compose.yml`:
```
    environment:
        XDEBUG_MODE: debug
```

## Commands

### `wp acf-sync clear`
Possibility to clear the ACF field group data saved in database to reset any mismatches between field changes in environment and JSON-files.


## Setup project first time

1. After cookiecutter has been used to create the project, set up git, git flow
   and the .env-files. In the root folder, run:
    ```
    make setup
    ```
2. Create first commit
    ```
    git add .
    git commit -m "Initial commit"
    ```
3. Push branches:
    ```
    git push -u origin develop
    git push -u origin main
    ```
4. Move on to Installation (Using Docker) and Deployment → Initial provisioning


## Deployment

### Initial provisioning
To set up the project on a server run the provisioning. Only needed once.

1. Make sure you have access to server and check provision files for correct info:
    - deploy/group_vars/webservers
    - deploy/stages/prod.yml
    - deploy/stages/stage.yml

2. Prepare local build environment:
```bash
cd deploy
python3 -m venv venv
. venv/bin/activate
pip install -r requirements.txt
ansible-galaxy install -r requirements.yml
```

3. Run provisioning on stage or prod:
- Stage: `ansible-playbook provision.yml -i stages/stage.yml`
- Prod: `ansible-playbook provision.yml -i stages/prod.yml`

4. After provisioning is setup on server, make sure your GitHub Actions workflow has access to the server (deploy SSH key / secrets configured)

5. Commit and push your changes and Happy deployment!

### Automated deployment (GitHub Actions)

Deployment runs through GitHub Actions (`.github/workflows/ci.yml` → `deploy.yml`).
`ci.yml` runs the quality gates (Trivy + zizmor) on every push and PR, and on
deployable refs it calls the reusable `deploy.yml`:

* **Push to `develop`** → deploys to **staging**
* **Push a `v*` tag** (e.g. via `git flow release finish`) → deploys to **production**
* **Manual run** (`deploy.yml` → "Run workflow") → deploy or roll back a chosen
  environment/ref via `workflow_dispatch`

The deploy target host and path come from `deploy/stages/*.yml` and
`deploy/group_vars/webservers` — adjust `ansistrano_deploy_to` / `ansible_user`
there to match your hosting (the deploy path varies by provider, e.g.
`/mnt/persist/www/...` vs a `/home/<user>/...` layout).

### Secrets

The workflows use these GitHub secrets and variables:

* `ACF_PRO_KEY` — ACF Pro licence, used to install `advanced-custom-fields-pro`.
  If your organisation provides it as an organisation-level secret it is already
  available; otherwise add it as a repository secret.
* `SLACK_BOT_TOKEN` — bot token for failure notifications (usually an
  organisation-level secret). Optional.
* `SSH_PRIVATE_KEY` — the deploy key, added as an **environment** secret on both
  the `stage` and `prod` environments (see below).
* `SLACK_CHANNEL` — repository **variable** (not a secret) naming the Slack
  channel for notifications; leave unset to disable. Optional.
* `GITHUB_TOKEN` is provided automatically by GitHub Actions.

**Setting up the deploy key:**

1. Generate an SSH keypair per environment — either with a secret manager (e.g.
   1Password, which stores the private key in the vault) or with
   `ssh-keygen -t ed25519 -C "github-actions-deploy"`.
2. Add each **public** key to the deploy user's `~/.ssh/authorized_keys` on the
   matching server (or send it to your hosting provider).
3. Add each **private** key as `SSH_PRIVATE_KEY` under
   **Settings → Environments → `stage` / `prod` → Environment secrets**
   (create the two environments first if they don't exist).

To enable Slack notifications, add the `SLACK_CHANNEL` variable under
**Settings → Secrets and variables → Actions → Variables**.

Full walkthrough:
[Setting up deployment with GitHub Actions](https://github.com/Frojd/Frojd-Bedrock/blob/main/docs/setting-up-deployment-with-github-actions.md).


## Documentation

* [Bedrock docs](https://roots.io/bedrock/docs/)
* [Sage docs](https://roots.io/sage/docs/)

## License


Company-Project is proprietary software. All rights reserved.

