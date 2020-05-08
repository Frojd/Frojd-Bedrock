#!/usr/bin/env bash
#
# Run composer inside of container
#
# Example usage `./scripts/composer.sh update`

docker-compose run --rm composer "$@"
