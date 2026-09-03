#!/usr/bin/env bash
#
# Export the remote database, download it, and import it into the local db.
# Usage: scripts/sync/fetch_remote_db.sh <stage>
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR/../.."

STAGE=$(echo "$1" | awk '{print toupper($0)}')
source "$SCRIPT_DIR/STAGES"

REMOTE_HOST=$(eval "echo $"${STAGE}_HOST)
REMOTE_USER=$(eval "echo $"${STAGE}_USER)
REMOTE_SRC_PATH=$(eval "echo $"${STAGE}_SRC_PATH)
REMOTE_TMP_PATH=$(eval "echo $"${STAGE}_TMP_PATH)

[[ -z $REMOTE_HOST ]] && echo "Unknown stage ${STAGE}" && exit 1
# Not all hosts have /mnt/persist, so the remote temp path is set per stage in
# STAGES via *_TMP_PATH. Fall back to /tmp when unset.
[[ -z $REMOTE_TMP_PATH ]] && REMOTE_TMP_PATH="/tmp"

# --skip-comments avoids the MariaDB sandbox-mode comment newer dumps prepend.
ssh $REMOTE_USER@$REMOTE_HOST "cd $REMOTE_SRC_PATH;
    wp --allow-root db export $REMOTE_TMP_PATH/latest.sql --skip-comments;"

scp $REMOTE_USER@$REMOTE_HOST:$REMOTE_TMP_PATH/latest.sql docker/files/db-dumps/latest.sql

ssh $REMOTE_USER@$REMOTE_HOST "rm $REMOTE_TMP_PATH/latest.sql;"

# Strip the sandbox-mode comment in case an older dump tool prepended it anyway; it aborts the import otherwise
docker compose exec -T db sh -c "sed -i '/^\/\*M!999999\\\\-.*\*\//d' /docker-entrypoint-initdb.d/latest.sql"

docker compose exec -T db mysql -uroot -pwp wp < docker/files/db-dumps/latest.sql
