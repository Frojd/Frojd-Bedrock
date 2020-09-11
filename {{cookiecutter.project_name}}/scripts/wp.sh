#!/usr/bin/env bash
#
# Execute wp-cli inside of container.
#
# Example usage `scripts/wp.sh db cli`

COMMAND="wp --allow-root $@"
docker-compose run --rm wp-cli sh -c "$COMMAND"
