# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  
  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "puppetlabs/ubuntu-14.04-64-nocm"

  # VMware Fusion - 80(HTTP), 443(HTTPS), 3306(MYSQL)
  config.vm.provider "vmware_fusion" do |vmware, override|
    override.vm.network "forwarded_port", guest: 80, host: 80
    override.vm.network "forwarded_port", guest: 3306, host: 3306
    override.vm.network "forwarded_port", guest: 443, host: 443
    # ---------- EDIT START - http://docs.vagrantup.com/v2/synced-folders/basic_usage.html
    override.vm.synced_folder "./", "/var/www/project"
    # ---------- EDIT END
  end

  # VirtualBox - 8080(HTTP), 4443(HTTPS), 3306(MYSQL)
  config.vm.provider "virtualbox" do |vbox, override|
    override.vm.network "forwarded_port", guest: 80, host: 8080, auto_correct: true
    override.vm.network "forwarded_port", guest: 3306, host: 3306
    override.vm.network "forwarded_port", guest: 443, host: 4443
    # ---------- EDIT START - http://docs.vagrantup.com/v2/synced-folders/basic_usage.html
    override.vm.synced_folder "./", "/var/www/project",  mount_options: ["dmode=777", "fmode=666"]
    # ---------- EDIT END
  end

  # Use host envrionment ssh keys
  config.ssh.forward_agent = true

  # Setup Bash scripts - run each time
  config.vm.provision :shell, :inline => "rm -rf ~/.bash_script &&
    mkdir ~/.bash_script &&
    cp /vagrant/vagrant-provision/bash/* ~/.bash_script/ &&
    chmod u+rwx ~/.bash_script/* -R &&
    printf 'export PATH=$PATH:~/.bash_script %b\n' >> ~/.bashrc", privileged: false, run: "always"

  # Provision scripts - run once
  config.vm.provision "shell", path: "vagrant-provision/provision.sh"
  config.vm.provision "shell", path: "vagrant-provision/provision_gems.sh"
  config.vm.provision "shell", path: "vagrant-provision/provision_npm.sh"
  config.vm.provision "shell", path: "vagrant-provision/conf/mysql_config.sh"
  
  # Provision Apache - run each time
  config.vm.provision "shell", path: "vagrant-provision/conf/apache_config.sh", run: "always"

end
