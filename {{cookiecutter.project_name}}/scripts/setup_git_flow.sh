#!/bin/bash

git config gitflow.branch.master 'main'
git flow init -d -t 'v'

chmod 755 ./git-hooks/bump-version.sh
ln -nfs ./git-hooks/bump-version.sh ./.git/hooks/post-flow-release-start
ln -nfs ./git-hooks/bump-version.sh ./.git/hooks/post-flow-hotfix-start
