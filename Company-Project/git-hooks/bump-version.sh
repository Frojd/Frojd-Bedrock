#!/bin/sh

# Bumps the version number to relevant files at the end of any release and hotfix start
#
# Positional arguments:
# $1 The version (including the version prefix)
# $2 The origin remote
# $3 The full branch name (including the release prefix)
# $4 The base from which this release is started
#
# The following variables are available as they are exported by git-flow:
#
# MASTER_BRANCH - The branch defined as Master
# DEVELOP_BRANCH - The branch defined as Develop

VERSION=$1

# Remove v prefix (if present)
VERSION=${VERSION#"v"}

ROOTDIR=$(git rev-parse --show-toplevel)


# Bump theme version

sed -i.bak 's/^Version:.*/Version: '$VERSION'/' $ROOTDIR/src/app/themes/main/style.css
rm $ROOTDIR/src/app/themes/main/style.css.bak

sed -i.bak 's/const APP_VERSION.*$/const APP_VERSION = "'$VERSION'";/' $ROOTDIR/config/application.php
rm $ROOTDIR/config/application.php.bak

# Commit changes
git commit -a -m "Bump version: $VERSION"

exit 0
