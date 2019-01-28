#!/usr/bin/env bash
#
# Execute wp-cli inside of container.
#
# Example usage `scripts/wp.sh db cli`

cd $(git rev-parse --show-toplevel)

COMMAND="cd /app; wp --allow-root $@"
docker-compose run --rm web bash -c "$COMMAND"

cd -