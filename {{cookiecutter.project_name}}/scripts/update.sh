#!/usr/bin/env bash
#
# Execute update of wp and all plugins, including language files and creating commits

# Check for diff, nothing else should be added to the following commits
if ! git diff-index --quiet HEAD --; then
    echo "You have changes in your tree, commit these changes before running update"
    exit 1
fi

# Run composer update
echo "Running Composer update"
./scripts/composer.sh update
git add .
git commit -m "Update wp and plugins with composer"

# Run update of plugins not added with composer
echo "Running update of other wp plugins"
./scripts/wp.sh plugin update gravityforms
git add .
git commit -m "Update wp plugins"

# Run update of all language files
echo "Running update of language files"
./scripts/wp.sh language plugin update --all
./scripts/wp.sh language core update
git add .
git commit -m "Update language files"


echo "Finished updating and creating commits"
