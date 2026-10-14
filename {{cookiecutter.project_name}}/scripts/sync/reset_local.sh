#!/usr/bin/env bash
#
# Rewrite the imported database for local use: replace the remote url with the
# local one, flush caches/permalinks, reindex search and reset the admin user.
# Usage: scripts/sync/reset_local.sh <stage>
#
# Honours a LOCAL_URL exported by the caller (the "Use SSL locally?" choice);
# otherwise defaults to http://$LOCAL_DOMAIN.
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR/../.."

STAGE=$(echo "$1" | awk '{print toupper($0)}')
source "$SCRIPT_DIR/STAGES"

REMOTE_DOMAIN=$(eval "echo $"${STAGE}_DOMAIN)

[[ -z $REMOTE_DOMAIN ]] && echo "Unknown stage ${STAGE}" && exit 1

# Normalize the remote domain to include a protocol for search-replace.
case "$REMOTE_DOMAIN" in
    http://*|https://*) ;;
    *) REMOTE_DOMAIN="https://$REMOTE_DOMAIN" ;;
esac

LOCAL_URL="${LOCAL_URL:-http://$LOCAL_DOMAIN}"

docker compose run --rm wp-cli sh -c "
    wp --allow-root search-replace $REMOTE_DOMAIN $LOCAL_URL --all-tables;
    wp --allow-root cache flush;
    wp --allow-root rewrite flush;
    wp --allow-root option set ep_host http://search:9200;
    wp --allow-root elasticpress sync;
    wp --allow-root plugin activate query-monitor;
    wp --allow-root plugin deactivate nginx-cache;
    wp --allow-root user update admin --user_pass=admin;"
