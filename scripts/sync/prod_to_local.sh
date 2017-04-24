#!/usr/bin/env bash
#
# Sync db and assets from prod to local
#
# SSH-keys is mandatory
# Example usage `scripts/sync/prod_to_local.sh`

read -p "This will replace your LOCAL database - Are you sure? [y/n]" -n 1 -r
echo # nl
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1
fi

cd $(git rev-parse --show-toplevel)

source scripts/sync/STAGES

ssh $LIVE_USERNAME@$LIVE_HOSTNAME "cd $LIVE_SRC_PATH;
    wp --allow-root db export /tmp/latest.sql;"

scp $LIVE_USERNAME@$LIVE_HOSTNAME:/tmp/latest.sql docker/files/db-dumps/latest.sql

./scripts/wp.sh db import /app/db-dumps/latest.sql
./scripts/wp.sh search-replace $LIVE_DOMAIN $LOCAL_DOMAIN
./scripts/wp.sh option set ep_host http://search:9200
./scripts/wp.sh cache flush
./scripts/wp.sh elasticpress index

rsync -re ssh $LIVE_USERNAME@$LIVE_HOSTNAME:$LIVE_UPLOAD_PATH/* src/app/uploads

cd -
