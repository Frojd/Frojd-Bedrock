# Company-Project

A short description of the project.

## Requirements

* [Docker](https://www.docker.com/) or [Valet](https://laravel.com/docs/8.x/valet)
* [Git flow](https://github.com/petervanderdoes/gitflow-avh)

## Installation (Using Docker)

1. Make sure you have Docker installed
2. In root folder, run:
    ```
    make init
    ```
    
2. Include this ip on your hosts-file

    ```
    127.0.0.1 example.com.test
    ```

    On windows you can run this command to append it:

    ```
    echo 127.0.0.1 example.com.test >> c:\windows\System32\drivers\etc\hosts
    ```

3. Start project

    ```
    docker-compose up
    ```

5. Visit your site on: [http://example.com.test:8080](http://example.com.test:8080)

## Installation (Using Valet)

1. Make sure you have [Valet](https://laravel.com/docs/8.x/valet) installed ([How to install laravel valet on mac](https://medium.com/modulr/how-to-install-laravel-valet-on-mac-f061ce2d095e))

2. In root folder, run:
    ```
    make init
    ```

    This will configure the docker setup as well as creating a local ROOT_FOLDER/.env which is the configuration file valet will use. Docker will keep running docker/config/web.env

3. Set up/configure a database

   Either create a local database and reconfigure ROOT_FOLDER/.env accordingly, or you could use docker:
   ```
   # Run in forground
   docker-compose up db

   # Run in background
   docker-compose up -d db
   ```

   If you are using docker, the DB_HOST should be set to "$LOCALIPADDRESS:$DOCKERPORT", this is configured by default by `make init`.

   Although default, this might be useful if migrating older projects:

       - To retrieve your local ip you can use `ipconfig getifaddr en0` on a mac

       - To retrieve your docker db-port, check the ports config of the db-container in docker-compose.yml

4. Install composer dependencies

   By running `composer install`, if versions are conflicting or if you do not have composer installed
   it is also possible to run supplied docker container: `docker-compose run composer install`

5. Create a valet link in the src-folder:
   ```
   cd src
   valet link example.com
   ```

6. Visit your site on `example.com.test`

7. (optional) Set up SSL
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

If you want remote-debugging for xdebug you need to make sure some ENV-vars is available 
when docker-compose build.
You could either add them to your local environment (e.g. .zshrc) or add a .env-file in the 
project root.
```
XDEBUG_REMOTE_HOST="111.111.111.111"
XDEBUG_IDEKEY="PHPSTORM"
```

.zshrc version, supporting dynamic IP´s:
```
export XDEBUG_REMOTE_HOST=$(ifconfig | grep "inet " | grep broadcast | head -n 1 | awk '{print $2}')
export XDEBUG_IDEKEY="PHPSTORM"
```

## Deployment

### Initial provisioning
To set up the project on a server run the provisioning. Only needed once. 

Prepare local build environment:
```bash
cd deploy
python3 -m venv venv
. venv/bin/activate
pip install -r requirements.txt
ansible-galaxy install -r requirements.yml
```
Run provisioning on stage or prod:
- Stage: `ansible-playbook provision.yml -i stages/stage`
- Prod: `ansible-playbook provision.yml -i stages/prod`

## Documentation

* [Bedrock docs](https://roots.io/bedrock/docs/)
* [Sage docs](https://roots.io/sage/docs/)

## License


Company-Project is proprietary software. All rights reserved.

