Based from https://github.com/SnceGroup/snce-docker repo, with some customizations

HOW TO START UP PROJECT FOR THE FIRST TIME: 
LAUNCH THIS COMMANDS INSIDE MAIN FOLDER:
docker-compose up -d
docker exec -it snce-docker-php-fpm bash
cd symfony 
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
exit

NOW YOU HAVE TO SET 777 PERMISSIONS INSIDE symfony folder and remove the files inside:
cd var
chmod 777 cache
cd cache 
rm -rf * 
cd ..
chmod 777 logs
cd logs 
rm -rf * 
cd ..
chmod 777 sessions
cd sessions 
rm -rf * 

Open browser and digit 127.0.0.1:8080 and... enjoy! 
In the first page you can find the 2 main routes and inside it you can find the routes everytime on the navbar on top.
