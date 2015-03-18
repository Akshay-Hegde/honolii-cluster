#!/usr/bin/env bash

#duplicate and replace vhosts
cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf.org
cp /vagrant/vm-scripts/conf/apache_vhost_dev.conf /etc/apache2/sites-available/000-default.conf

#setup xdebug config
> /etc/php5/mods-available/xdebug.ini
printf "zend_extension=xdebug.so" >> /etc/php5/mods-available/xdebug.ini
printf "xdebug.remote_enable=1 %b\n" >> /etc/php5/mods-available/xdebug.ini
printf "xdebug.remote_connect_back=1 %b\n" >> /etc/php5/mods-available/xdebug.ini
printf "xdebug.idekey = \"sublime.xdebug\" %b\n" >> /etc/php5/mods-available/xdebug.ini

#restart apache
service apache2 reload