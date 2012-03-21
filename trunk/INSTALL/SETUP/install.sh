apt-get -y install php5-sqlite
apache2ctl restart
chmod -R 777 app/cache
chmod -R 777 app/logs
service apache2 restart

