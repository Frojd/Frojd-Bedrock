#!/usr/bin/env bash
#
# Stores current db-dump to docker/files/db-dumps/latest.sql
#
# It can be useful to save the current state
# to allow you to rebuild the db container without data-loss.
#
# Or if you want to send it to a friend you could just send it over and if they put
# it in docker/files/db-dumps it will be read after docker-compose build -- Sharing is caring.

cd $(git rev-parse --show-toplevel)

docker-compose run web bash -c "cd /app; wp --allow-root db export db-dumps/latest.sql"

cd -