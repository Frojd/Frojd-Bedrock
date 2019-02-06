setup:
	git flow init
	cp docker/config/web.example.env docker/config/web.env
	ln -nfs $(PWD)/.githooks/pre-push.sh .git/hooks/pre-push
	ln -nfs $(PWD)/.githooks/pre-commit.sh .git/hooks/pre-commit
	ln -nfs $(PWD)/.githooks/bump-version.sh .git/hooks/post-flow-release-start
	ln -nfs $(WPD)/.githooks/bump-version.sh .git/hooks/post-flow-hotfix-start
