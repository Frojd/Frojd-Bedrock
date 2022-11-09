#!/bin/bash

git config gitflow.branch.master 'main'
git flow init -d -t 'v'

chmod +x $PWD/git-hooks/bump-version.sh
ln -nfs $PWD/git-hooks/bump-version.sh .git/hooks/post-flow-release-start
ln -nfs $PWD/git-hooks/bump-version.sh .git/hooks/post-flow-hotfix-start

echo "Git flow and git hooks has been added"

exit 0
