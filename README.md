Based from https://github.com/SnceGroup/snce-docker repo, with some customizations

HOW TO START UP PROJECT FOR THE FIRST TIME: 
LAUNCH THIS COMMANDS INSIDE MAIN FOLDER:
docker-compose up -d
docker exec -it snce-docker-php-fpm bash
cd symfony 
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

NOW YOU HAVE TO SET 777 PERMISSIONS INSIDE symfony folder:
chmod 777 var/cache
chmod 777 var/logs
chmod 777 var/sessions

Open browser and digit 127.0.0.1:8080 and... enjoy! 
In the first page you can find the 2 main routes and inside it you can find the routes everytime on the navbar on top.
