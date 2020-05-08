#!/usr/bin/env bash
#
# Generate development ssl certificates using mkcert
# NOTE: you need mkcert installed locally for this to work! 

if ! [ -d .git ]; then
    echo "Please initialize a git repo in this project before running this command"
    exit -1; 
fi

cd $(git rev-parse --show-toplevel)

which mkcert
if [ $? -eq 0 ]; then
    printf "\n* Found mkcert in path!\n\n"
else
    echo "* Can't find mkcert binary in PATH, please install, then run this script again: https://github.com/FiloSottile/mkcert"
    exit -1
fi

read -p "Warning: the rootCA-key.pem file that mkcert automatically installs locally 
gives complete power to intercept secure requests from your machine. Do not share it. 

Continue (y/n)?" CONT

if ! [ "$CONT" = "y" ]; then
    exit -1; 
fi

DOMAIN="example.com.test"
HTTP_PORT=8080
HTTPS_PORT=8081

CONTAINER_ID=$(eval 'docker-compose exec web bash -c "hostname"' | tr -d '\r')
CONTAINER_CA_ROOT=$(eval 'docker-compose exec web bash -c "mkcert -CAROOT"' | tr -d '\r')
LOCAL_CA_ROOT=$(eval 'mkcert -CAROOT' | tr -d '\r')

docker-compose exec web bash -c "cd /etc/nginx/certs/ ; 
    mkcert '*.$DOMAIN' $DOMAIN localhost 127.0.0.1 ::1 ; 
    mv _wildcard.$DOMAIN+4.pem dev-cert.pem ; 
    mv _wildcard.$DOMAIN+4-key.pem dev-key.pem ; 
    sed -i '/#mkcert/s/#mkcert//g' /etc/nginx/sites-enabled/default ; 
    service nginx restart"

docker-compose exec web bash -c "cd app;
    wp --allow-root search-replace http://${DOMAIN}:${HTTP_PORT} https://${DOMAIN}:${HTTPS_PORT}"


docker cp "${CONTAINER_ID}:${CONTAINER_CA_ROOT}/rootCA-key.pem" "${LOCAL_CA_ROOT}/rootCA-key.pem"
docker cp "${CONTAINER_ID}:${CONTAINER_CA_ROOT}/rootCA.pem" "${LOCAL_CA_ROOT}/rootCA.pem"

mkcert -install

sed -i '' '/http/s/http/https/g' docker/config/web.env
sed -i '' "/$HTTP_PORT/s/$HTTP_PORT/$HTTPS_PORT/g" docker/config/web.env

printf "\n * Done! Restart your browser, then visit: \nhttps://${DOMAIN}:${HTTPS_PORT}\n\n"

cd -