# {{cookiecutter.project_name}}

{{cookiecutter.description}}

## Requirements

* Docker
* [Git flow](https://github.com/petervanderdoes/gitflow-avh)

## Installation


1. Make sure you have requirements installed
2. In root folder, run:
    ```
    make init
    ```
    
2. Include this ip on your hosts-file

    ```
    127.0.0.1 {{cookiecutter.domain_prod}}.test
    ```

    On windows you can run this command to append it:

    ```
    echo 127.0.0.1 {{cookiecutter.domain_prod}}.test >> c:\windows\System32\drivers\etc\hosts
    ```

3. Start project

    ```
    docker-compose up
    ```

5. Visit your site on: [http://{{cookiecutter.domain_prod}}.test:{{cookiecutter.docker_web_port}}](http://{{cookiecutter.domain_prod}}.test:{{cookiecutter.docker_web_port}})

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

{% if cookiecutter.software_license != 'proprietary' %}
{{cookiecutter.project_name}} is released under the {{cookiecutter.software_license}} license.
{% else %}
{{cookiecutter.project_name}} is proprietary software. All rights reserved.
{% endif %}
