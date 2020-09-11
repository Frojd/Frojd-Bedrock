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
project_name [Client-Project]: Example-Project
project_slug [example_project]:
author_name [You]:
email [you@example.com]:
description [A short description of the project.]: Example description.
domain_prod [example.com]:
domain_stage [stage.example.com]:
ssh_prod [user@prod-server]:
ssh_stage [user@stage-server]:
db_name_prod [prod_db]:
db_name_stage [stage_db]:
docker_web_port [8081]:
docker_db_port [5433]:
version [0.1.0]: 1.0.0
Select software_license:
1 - MIT
2 - proprietary
Choose from 1, 2 [1]: 1
```

4. Done! 

## Theme (Sage)

Default theme is based on [Sage](https://github.com/roots/sage/tree/master/). (version 9 alpha)

## Versioning

This boilerplate uses [semantic versioning](http://semver.org/).


## Contributing

Want to contribute? Awesome. Just send a pull request.


## License

Fröjd Bedrock Boilerplate is released under the [MIT License](http://www.opensource.org/licenses/MIT).