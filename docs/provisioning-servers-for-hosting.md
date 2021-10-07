# Provisioning webserver

In this guide we'll explain how to use the included provisioning script to install a Frojd-Bedrock generated application on a server.


## Requirements (webserver, aka ansible host)

The server should have these applications/packages installed:
- Linux (Ubuntu 20.04+ is preffered)
- Nginx
- PHP-FPM
- PHP 7.4+
- Mysql

Linux should have these users (with passwordless login using RSA keys):
- "root" - Used when provisioning web server
- "deploy" - Used for deployment

Configuration:
- Systemd jobs for Nginx and PHP-FPM
- Nginx configuration are stored at `/mnt/persist/nginx/conf.d/*`
- Your web applications are stored at `/mnt/persist/www/*`

## Requirements (your computer, aka control node)

- A fully generated Frojd-Bedrock project
- Rsync installed
- A MacOS or Linux computer ([Ansible does not support Windows](http://blog.rolpdog.com/2020/03/why-no-ansible-controller-for-windows.html))
- Access to the webserver over ssh with both the "root" and "deploy" user

## Guide

- Begin by going to the deploy dir in your project and install [Ansible](https://www.ansible.com/)
    ```
    >>> cd deploy
    >>> python3 -m venv venv
    >>> . venv/bin/activate
    >>> pip install -r requirements.txt
    ```

- Make sure you can connect to the server by pinging it
    ```
    >>> ansible -i stages/stage.yml webservers -m ping
    stage1 | SUCCESS => {
        "ansible_facts": {
            "discovered_interpreter_python": "/usr/bin/python3"
        },
        "changed": false,
        "ping": "pong"
    }
    ```

- After this, we install [Ansistrano](https://ansistrano.com/): 
    ```
    >>> ansible-galaxy install -r requirements.yml
    ```
- Now that we have everything installed, lets run the provisioning:
- For stage
    ```
    >>> ansible-playbook provision.yml -i stages/stage.yml
    ```
- For prod
    ```
    >>> ansible-playbook provision.yml -i stages/prod.yml
    ```
- This will script will run the necessary steps to make sure your application is ready to be deployed
- The next step is to run a deploy to sent our application to the server and start it
- For stage
    ```
    >>> ansible-playbook deploy.yml -i stages/stage.yml
    ```
- For prod
    ```
    >>> ansible-playbook deploy.yml -i stages/prod.yml
    ```
- Done!

## Note
- This script does not perform server provisioning, only application provisioning. But there are plenty of [guides on how to do this](https://clouding.io/hc/en-us/articles/360013788600-How-to-provision-Ubuntu-server-with-Ansible-scripts).
