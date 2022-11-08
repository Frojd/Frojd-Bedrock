# Frojd-Bedrock

The Fröjd fork of [Bedrock](https://roots.io/bedrock/)

Bedrock is a modern WordPress stack inspired by [Twelve-Factor App](http://12factor.net/) including the [WordPress specific version](https://roots.io/twelve-factor-wordpress/).


## Features

- Wordpress
- Bedrock
- Sage
- Docker and Docker-compose support
- [12-Factor](https://12factor.net/) based
- Settings primed for production
- Third part integrations:
    - [Sentry](https://sentry.io/)
    - [Circle CI](https://circleci.com/)
- Deploy scripts using [ansistrano](https://github.com/ansistrano)
- Orchestration using [ansible](https://github.com/ansible/ansible)
- Scripts for syncing data from remote to local machine


## Usage

1. Install cookiecutter, there are several options:
    - `pip install cookiecutter`
    - `brew install cookiecutter`

2. Generate project:
```
cookiecutter https://github.com/Frojd/Frojd-Bedrock.git
```

3. Insert your custom vars:
```
repo_name [Frojd/Company-project.se]: Org/Example-project.se
project_name [Example-Project]:
project_slug [example_project]:
description [A short description of the project.]: Example description.
public_site_name [Example Project]
public_site_description: Example public description
domain_prod [example.com]:
domain_stage [stage.example.com]:
ssh_prod [example.com]: host.com
ssh_stage [stage.example.com]: stage.host.com
deploy_dir [example.com]: host.com
db_name_prod [example_com]: host_com
db_name_stage [example_com]: host_com
docker_web_port [8080]:
docker_web_ssl_port [8081]
docker_db_port [8082]:
docker_search_port [8083]:
version [0.1.0]: 1.0.0
Select software_license:
1 - MIT
2 - proprietary
Choose from 1, 2 [1]: 1
```

4. Done!


## Update Example
When changes have been made make sure to update Company-Project. This will overwrite all files in Company-Project
```
cookiecutter . -f --no-input
```


## Theme (Sage)

Default theme is based on [Sage](https://github.com/roots/sage/tree/master/). (version 9 alpha)

## Versioning

This boilerplate uses [semantic versioning](http://semver.org/).


## Guides

- [Provision and configure a webserver for hosting](https://github.com/Frojd/Frojd-Bedrock/blob/master/docs/provisioning-servers-for-hosting.md)
- [Setting up deployment on CircleCI](https://github.com/Frojd/Frojd-Bedrock/blob/master/docs/setting-up-deployment-with-circleci.md)


## Contributing

Want to contribute? Awesome. Just send a pull request.


## License

Fröjd Bedrock Boilerplate is released under the [MIT License](http://www.opensource.org/licenses/MIT).
