init:
	git flow init
	./scripts/setup_docker_config.sh
	chmod 755 $(PWD)/git-hooks/bump-version.sh
	ln -nfs $(PWD)/git-hooks/bump-version.sh .git/hooks/post-flow-release-start
	ln -nfs $(PWD)/git-hooks/bump-version.sh .git/hooks/post-flow-hotfix-start
