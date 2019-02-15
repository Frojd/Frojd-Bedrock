setup:
	git flow init
	cp docker/config/web.example.env docker/config/web.env
	chmod 755 $(PWD)/git-hooks/bump-version.sh
	ln -nfs $(PWD)/git-hooks/bump-version.sh .git/hooks/post-flow-release-start
	ln -nfs $(PWD)/git-hooks/bump-version.sh .git/hooks/post-flow-hotfix-start
