#Mise à jour
apt-get update
apt-get upgrade -y

#Instalaltion de phpmyadmin
db_root_password="online@2017"

mysql --user=root --password=$db_root_password <<_EOF_  
  CREATE USER 'phpmyadmin'@'localhost' IDENTIFIED BY '${db_root_password}';
  GRANT ALL PRIVILEGES ON phpmyadmin.* TO 'phpmyadmin'@'localhost' with grant option;
  FLUSH PRIVILEGES;
_EOF_

debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $db_root_password"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $db_root_password"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $db_root_password"
debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none"
apt-get -y install phpmyadmin