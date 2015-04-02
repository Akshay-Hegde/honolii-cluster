# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  
  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "puppetlabs/ubuntu-14.04-64-nocm"

  config.vm.provider "vmware_fusion" do |vmware, override|
		override.vm.network "forwarded_port", guest: 80, host: 80
	end

	config.vm.provider "virtualbox" do |vbox, override|
	  override.vm.network "forwarded_port", guest: 80, host: 8080, auto_correct: true
	  override.vm.network "forwarded_port", guest: 3306, host: 3306
	  override.vm.synced_folder ".", "/vagrant",  mount_options: ["dmode=777", "fmode=666"]
	end

  # Provision script - run once
  config.vm.provision "shell", path: "vm-scripts/provision_vagrant_dev.sh"
  
  # Provision Apach - run each time
  config.vm.provision "shell", path: "vm-scripts/apache_dev.sh", run: "always"
  
  # Provision Tasks - run each time
  config.vm.provision "shell", path: "vm-scripts/tasks_dev.sh", privileged: false, run: "always"

end
