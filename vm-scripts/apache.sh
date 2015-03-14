#!/usr/bin/env bash

printf "\nCopying default.vhost file to /etc/apache2/sites-available/default\n"
cp /vagrant/vm-scripts/default.vhost /etc/apache2/sites-available/default

printf "\nReloading apache\n"
service apache2 reload