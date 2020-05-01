Based from https://github.com/SnceGroup/snce-docker repo, with some customizations<br/>

HOW TO START UP PROJECT FOR THE FIRST TIME: <br/>
LAUNCH THESE COMMANDS INSIDE MAIN FOLDER:<br/>
docker-compose up -d <br/>
docker exec -it snce-docker-php-fpm bash <br/>
cd symfony <br/>
php bin/console doctrine:database:create <br/>
php bin/console doctrine:schema:update --force <br/>
exit <br/><br/>

NOW YOU HAVE TO SET 777 PERMISSIONS INSIDE symfony folder and remove the files inside: <br/>
cd var <br/>
chmod 777 cache <br/>
cd cache  <br/>
rm -rf *  <br/>
cd .. <br/>
chmod 777 logs <br/>
cd logs  <br/>
rm -rf *  <br/>
cd .. <br/>
chmod 777 sessions <br/>
cd sessions  <br/>
rm -rf *  <br/> <br/>

Open browser and digit 127.0.0.1:8080 and... enjoy!  <br/>
In the first page you can find the 2 main routes and inside it you can find the routes everytime on the navbar on top.
