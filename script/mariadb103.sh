#Mise à jour
apt-get update
apt-get upgrade -y

#Installation de maridb
apt-get -y install software-properties-common
apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
add-apt-repository 'deb [arch=amd64,arm64,ppc64el] http://mariadb.mirrors.ovh.net/MariaDB/repo/10.3/ubuntu bionic main'
apt-get update
export DEBIAN_FRONTEND=noninteractive
debconf-set-selections <<< "mariadb-server mariadb-server/root_password password $ROOTDBPWD"
debconf-set-selections <<< "mariadb-server mariadb-server/root_password_again password $ROOTDBPWD"
apt-get -y install mariadb-server



#Mysql secure avec mot de passe
db_root_password="online@2017"
mysql --user=root <<_EOF_
UPDATE mysql.user SET Password=PASSWORD('${db_root_password}') WHERE User='root';
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
FLUSH PRIVILEGES;
_EOF_
