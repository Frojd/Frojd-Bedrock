curl -Lo master.zip 'https://github.com/Frojd/Frojd-Gulp/archive/master.zip'

unzip master.zip
rm master.zip

mv Frojd-Gulp-master/gulp gulp
mv Frojd-Gulp-master/gulpfile.js gulpfile.js
mv gulp/config.template.js gulp/config.js
rm -rf Frojd-Gulp-master
