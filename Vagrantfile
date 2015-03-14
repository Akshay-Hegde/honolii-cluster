# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  
  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "puppetlabs/ubuntu-14.04-64-nocm"
  
  # Provision script - run once
  config.vm.provision "shell", path: "vm-scripts/provision.sh"
  
  # Copy the vhost file to default and reload apache - run every vagrant up
  #config.vm.provision "shell", path: "vm-scripts/apache.sh"
  
  # Compass watch - don't run as sudo
  #config.vm.provision "shell", path: "vm-scripts/compass.sh", privileged: false

end
