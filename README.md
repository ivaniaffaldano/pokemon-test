Based from https://github.com/SnceGroup/snce-docker repo, with some customizations

HOW TO START UP PROJECT FOR THE FIRST TIME: 

docker-compose up -d
docker exec -it snce-docker-php-fpm bash
cd symfony 
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
chmod 777 var/cache
chmod 777 var/logs
chmod 777 var/sessions
