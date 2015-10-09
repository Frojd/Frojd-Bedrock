cd web/app/themes

curl -Lo master.zip 'https://github.com/Frojd/WP-Theme-Boilerplates/archive/master.zip'

unzip master.zip
rm master.zip

mv WP-Theme-Boilerplates-master/frojdtheme2015 main
rm -rf WP-Theme-Boilerplates-master
