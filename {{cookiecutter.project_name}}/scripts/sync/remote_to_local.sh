#!/usr/bin/env bash
#
# Sync db and assets from remote to local
#
# SSH-keys is mandatory
# Example usage `scripts/sync/remote_to_local.sh prod`

cd $(git rev-parse --show-toplevel)

STAGE=$(echo $1 | awk '{print toupper($0)}')

source scripts/sync/STAGES

REMOTE_HOST=$(eval "echo $"${STAGE}_HOST)
REMOTE_USERNAME=$(eval "echo $"${STAGE}_USERNAME)
REMOTE_SRC_PATH=$(eval "echo $"${STAGE}_SRC_PATH)
REMOTE_UPLOAD_PATH=$(eval "echo $"${STAGE}_UPLOAD_PATH)
REMOTE_DOMAIN=$(eval "echo $"${STAGE}_DOMAIN)

[[ -z $REMOTE_HOST ]] && echo "Unknown stage ${STAGE}" && cd - && exit 1

read -p "This will replace your LOCAL database from stage ${STAGE} - Are you sure? [y/n]" -n 1 -r
echo # nl
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    cd -
    [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1
fi


ssh $REMOTE_USERNAME@$REMOTE_HOST "cd $REMOTE_SRC_PATH;
    wp --allow-root db export /tmp/latest.sql;"

scp $REMOTE_USERNAME@$REMOTE_HOST:/tmp/latest.sql docker/files/db-dumps/latest.sql

docker-compose exec web bash -c "cd app;
    wp --allow-root db import /app/db-dumps/latest.sql;
    wp --allow-root search-replace $REMOTE_DOMAIN $LOCAL_DOMAIN;
    wp --allow-root cache flush;
    wp --allow-root option set ep_host http://search:9200
    wp --allow-root elasticpress index
    wp --allow-root plugin activate debug-bar;
    wp --allow-root plugin deactivate nginx-cache;
    wp --allow-root user update admin --user_pass=admin;"

rsync -re ssh $REMOTE_USERNAME@$REMOTE_HOST:$REMOTE_UPLOAD_PATH/* src/app/uploads/

cd -
