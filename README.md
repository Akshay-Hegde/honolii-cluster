# Honolii Cluster - PyroCMS Pro
This application is the CMS for a few managed websites.
* emeehan.com - Edward Meehan
* autowrapshawaii.com - John Allen Sign Company
* johnallensigncompany.com - John Allen Sign Company
* northcountyeyesurgery.com - North County Eye Surgery
* mxtrackbuilders.com - MX Track Builders
* wetumka.net - Wetumka Interactive

## Getting Started
In order to start development on this project you will need to install a few things on your local computer.
* VM Software:
	* [VMware](http://www.vmware.com/) - Fusion requires a [vagrant plugin to work](http://www.vagrantup.com/vmware).
	* [Virtual Box](https://www.virtualbox.org/) - Free alternative to Fusion, just not as powerful.
* [Vagrant](https://www.vagrantup.com/) - builds VM envrionment for development with a single command

Clone the repo, and open up your terminal and navigate to this project directory. Make sure you have disabled any local services listening on port 80 (such as local apache). Vagrant will create a VM development envrionment and forward port 80 to your new VM, this way you can still use your local hosts file and use private networking while still being able to connect from other devices on your local network (browser testing, device testing).

```
cd your/project/location
vagrant up
```

This should start the vagrant build, this VM will be provisioned with all the software needed to run the applicatoin, along with other tools like Grunt, Ruby, SASS, etc. Grunt should already be setup for automation, so after vagrant has finished doing its thing you can SSH into it and run any Grunt tasks you need.

```
vagrant ssh
```

Now you should be loged into your new VM dev envrionment, simply change to the /vagrant directory to access all your Grunt tasks.

```
cd /vagrant
```

Thats it, you should be ready to rock!