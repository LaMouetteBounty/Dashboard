#Mise à jour
apt-get update
apt-get upgrade -y

#Installation d'apache
apt-get -y install apache2

#droit du dossier html
chgrp www-data /var/www/html
adduser vagrant www-data
chmod -R g+rw /var/www/html