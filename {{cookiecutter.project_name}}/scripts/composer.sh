#!/usr/bin/env bash
#
# Run composer inside of container
#
# Example usage `scripts/composer.sh update`

cd $(git rev-parse --show-toplevel)

COMMAND="cd /app; composer $@"
docker-compose run --rm web bash -c "$COMMAND"

cd -