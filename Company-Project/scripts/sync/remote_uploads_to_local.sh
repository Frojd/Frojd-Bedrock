#!/usr/bin/env bash
#
# Sync the uploads directory from the remote to the local project.
# Usage: scripts/sync/remote_uploads_to_local.sh <stage>
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR/../.."

STAGE=$(echo "$1" | awk '{print toupper($0)}')
source "$SCRIPT_DIR/STAGES"

REMOTE_HOST=$(eval "echo $"${STAGE}_HOST)
REMOTE_USER=$(eval "echo $"${STAGE}_USER)
REMOTE_UPLOAD_PATH=$(eval "echo $"${STAGE}_UPLOAD_PATH)

[[ -z $REMOTE_HOST ]] && echo "Unknown stage ${STAGE}" && exit 1

rsync -re ssh $REMOTE_USER@$REMOTE_HOST:$REMOTE_UPLOAD_PATH/* src/app/uploads/
