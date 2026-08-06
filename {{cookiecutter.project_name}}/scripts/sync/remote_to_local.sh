#!/usr/bin/env bash
#
# Sync db and assets from remote to local.
#
# SSH-keys are mandatory.
# Example usage `scripts/sync/remote_to_local.sh prod`
#
# This is the orchestrator: it asks the questions and runs the steps. Each step
# is also runnable on its own with the stage as an argument
# (e.g. `scripts/sync/reset_local.sh prod`):
#   - fetch_remote_db.sh <stage>          export + download + import the remote db
#   - reset_local.sh <stage>              rewrite urls and reset plugins/admin
#   - remote_uploads_to_local.sh <stage>  sync the uploads directory
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR/../.."

STAGE=$(echo "$1" | awk '{print toupper($0)}')
source "$SCRIPT_DIR/STAGES"

[[ -z $(eval "echo $"${STAGE}_HOST) ]] && echo "Unknown stage ${STAGE}" && exit 1

# Pick the local target url; default to http, use SSL if the dev opts in. This
# is exported so the reset step rewrites urls to the chosen scheme.
export LOCAL_URL="http://$LOCAL_DOMAIN"
read -p "Use SSL locally? [y/n]" -n 1 -r
echo # nl
if [[ $REPLY =~ ^[Yy]$ ]]
then
    export LOCAL_URL="https://${SSL_LOCAL_DOMAIN:-$LOCAL_DOMAIN}"
fi

read -p "This will replace your LOCAL database from stage ${STAGE} - Are you sure? [y/n]" -n 1 -r
echo # nl
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1
fi

"$SCRIPT_DIR/fetch_remote_db.sh" "$1"
"$SCRIPT_DIR/reset_local.sh" "$1"

read -p "This will sync all uploads from stage ${STAGE} to src/app/uploads/ - Are you sure? [y/n]" -n 1 -r
echo # nl
if [[ $REPLY =~ ^[Yy]$ ]]
then
    "$SCRIPT_DIR/remote_uploads_to_local.sh" "$1"
fi
