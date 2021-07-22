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
rm $ROOTDIR/OrderedDict([('project_name', 'Company-Project'), ('project_slug', 'company_project'), ('description', 'A short description of the project.'), ('site_name', 'Project'), ('site_description', 'A short description for public display of site used in manifest'), ('domain_prod', 'example.com'), ('domain_stage', 'stage.example.com'), ('ssh_host_prod', 'example.com'), ('ssh_host_stage', 'stage.example.com'), ('db_name_prod', 'company_project'), ('db_name_stage', 'company_project'), ('docker_web_port', '8080'), ('docker_web_ssl_port', '8081'), ('docker_db_port', '8082'), ('docker_search_port', '8083'), ('version', '0.1.0'), ('software_license', 'proprietary'), ('_copy_without_render', ['*.git']), ('_template', '.')])/src/app/themes/main/style.css.bak

sed -i.bak 's/^\( *\)"version": .*/\1"version": "'$VERSION'",/' $ROOTDIR/src/app/themes/main/frontend/package.json
rm $ROOTDIR/OrderedDict([('project_name', 'Company-Project'), ('project_slug', 'company_project'), ('description', 'A short description of the project.'), ('site_name', 'Project'), ('site_description', 'A short description for public display of site used in manifest'), ('domain_prod', 'example.com'), ('domain_stage', 'stage.example.com'), ('ssh_host_prod', 'example.com'), ('ssh_host_stage', 'stage.example.com'), ('db_name_prod', 'company_project'), ('db_name_stage', 'company_project'), ('docker_web_port', '8080'), ('docker_web_ssl_port', '8081'), ('docker_db_port', '8082'), ('docker_search_port', '8083'), ('version', '0.1.0'), ('software_license', 'proprietary'), ('_copy_without_render', ['*.git']), ('_template', '.')])/src/app/themes/main/frontend/package.json.bak

sed -i.bak 's/const APP_VERSION.*$/const APP_VERSION = "'$VERSION'";/' $ROOTDIR/config/application.php
rm $ROOTDIR/OrderedDict([('project_name', 'Company-Project'), ('project_slug', 'company_project'), ('description', 'A short description of the project.'), ('site_name', 'Project'), ('site_description', 'A short description for public display of site used in manifest'), ('domain_prod', 'example.com'), ('domain_stage', 'stage.example.com'), ('ssh_host_prod', 'example.com'), ('ssh_host_stage', 'stage.example.com'), ('db_name_prod', 'company_project'), ('db_name_stage', 'company_project'), ('docker_web_port', '8080'), ('docker_web_ssl_port', '8081'), ('docker_db_port', '8082'), ('docker_search_port', '8083'), ('version', '0.1.0'), ('software_license', 'proprietary'), ('_copy_without_render', ['*.git']), ('_template', '.')])/config/application.php.bak

# Commit changes
git commit -a -m "Bump version: $VERSION"

exit 0
