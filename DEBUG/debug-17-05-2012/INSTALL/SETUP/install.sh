apt-get -y install php5-sqlite
apache2ctl restart
chmod -R 777 ../../app/cache
chmod -R 777 ../../app/logs
/etc/init.d/apache2 restart

