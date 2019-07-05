#Mise à jour
apt-get update
apt-get upgrade -y


#installation de php
apt-get -y install php
apt-get -y install libapache2-mod-php 
apt-get -y install php-cli 
apt-get -y install php-mysql 
apt-get -y install php-gd 
apt-get -y install php-imagick 
apt-get -y install php-recode 
apt-get -y install php-tidy 
apt-get -y install php-xmlrpc 
apt-get -y install php-zip 
apt-get -y install php-mbstring

#configuration de php
sed -i '/^max_execution_time/{s/30/360/i}' /etc/php/7.2/apache2/php.ini
sed -i '/^memory_limit/{s/128M/256M/i}' /etc/php/7.2/apache2/php.ini
sed -i '/^display_errors/{s/Off/On/i}' /etc/php/7.2/apache2/php.ini
sed -i '/^post_max_size/{s/8M/64M/i}' /etc/php/7.2/apache2/php.ini
sed -i '/^upload_max_filesize/{s/2M/500M/i}' /etc/php/7.2/apache2/php.ini
sed -i '/^max_input_time/{s/60/360/i}' /etc/php/7.2/apache2/php.ini
sed -i '/^max_input_time/{s/60/360/i}' /etc/php/7.2/apache2/php.ini
sed -i 's/;date.timezone =/date.timezone = Europe\/Paris/' /etc/php/7.2/apache2/php.ini