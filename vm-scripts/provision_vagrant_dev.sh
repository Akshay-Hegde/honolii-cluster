#!/usr/bin/env bash

# Determine if this machine has already been provisioned
# Basically, run everything after this command once, and only once
if [ -f "/var/vagrant_provision" ]; then 
    exit 0
fi

# config vars
db='wetumkadb'

# config functions
function say {
    printf "\n--------------------------------------------------------\n"
    printf "\t$1"
}

export DEBIAN_FRONTEND=noninteractive
# Get packages
say "Getting latest packages"
    apt-get update >/dev/null 2>&1

# Install packages
say "Installing packages"
    apt-get install -y curl apache2 mysql-server git-core unzip >/dev/null 2>&1

# Install Apache
say "Setting up Apache."    
    # Remove /var/www path
    rm -rf /var/www/html
    # Symbolic link to /vagrant/site path
    ln -fs /vagrant /var/www/html
    # Enable mod_rewrite
    a2enmod rewrite
    # Restart Apache
    printf "Restarting Apache"
    printf "ServerName localhost" >> /etc/apache2/apache2.conf
    service apache2 restart

# Install mysql
say "Setting up MySQL."
    sed -i -e 's/127.0.0.1/0.0.0.0/' /etc/mysql/my.cnf
    service mysql restart
    mysql -u root mysql <<< "GRANT ALL ON *.* TO 'root'@'%'; FLUSH PRIVILEGES;"
    # Create database
    printf "Creating the database '$db'"
    mysql -u root -e "create database $db"
    # Populate database
    printf "Populating Database"
    mysql -u root -D $db < /vagrant/vm-scripts/db/$db.sql
    service mysql restart

say "Installing PHP Modules"
    apt-get install -y php5 php5-cli php5-common php5-dev php5-json php5-xdebug php5-gd libapache2-mod-php5 php5-mysql php5-curl >/dev/null 2>&1

# Restart Apache
say "Restarting Apache"
    #printf 'ServerName localhost' >> /etc/apache2/apache2.conf
    service apache2 restart

# Installing NodeJS & Grunt
say "Installing NodeJS & NPM"
    apt-get install -y software-properties-common >/dev/null 2>&1
    add-apt-repository ppa:chris-lea/node.js >/dev/null 2>&1
    apt-get update >/dev/null 2>&1
    apt-get install -y nodejs >/dev/null 2>&1
    npm update -g npm >/dev/null 2>&1
    npm install -g grunt-cli >/dev/null 2>&1

# Installing Ruby
say "Installing Ruby Packages"
    apt-get update >/dev/null 2>&1
    apt-get install -y git-core curl zlib1g-dev build-essential libssl-dev libreadline-dev libyaml-dev libsqlite3-dev sqlite3 libxml2-dev libxslt1-dev libcurl4-openssl-dev python-software-properties libffi-dev >/dev/null 2>&1

# Installing Ruby
say "Installing RVM"
    su - vagrant -c 'gpg --keyserver hkp://keys.gnupg.net --recv-keys D39DC0E3'
    su - vagrant -c 'sudo curl -sSL https://get.rvm.io | bash -s stable >/dev/null 2>&1'
    su - vagrant -c './.rvm/scripts/rvm'
    su - vagrant -c 'echo "source ~/.rvm/scripts/rvm" >> ~/.bashrc'
    su - vagrant -c 'rvm requirements'

say "Installing Ruby"
    su - vagrant -c 'rvm install 2.1.3 >/dev/null 2>&1'
    su - vagrant -c 'rvm use 2.1.3 --default'
    su - vagrant -c 'rvm rubygems current'

say "Installing SASS + COMPASS"
    su - vagrant -c 'gem install sass'
    su - vagrant -c 'gem install compass'

# say "Installing Other Gems"
#     su - vagrant -c 'gem install capistrano'

# Let this script know not to run again
touch /var/vagrant_provision

say "ALL DONE!"