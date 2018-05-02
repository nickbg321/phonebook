Vagrant.configure("2") do |config|
  details = {
    domain: 'phonebook.local',
    webroot: 'web',
    timezone: 'Europe/Sofia'
  }

  use_nfs = false;

  config.vm.box = "ubuntu/artful64"

  config.vm.network "private_network", ip: "192.168.83.137"
  config.vm.network "forwarded_port", guest: 80, host: 80
  config.vm.network "forwarded_port", guest: 443, host: 443
  config.vm.network "forwarded_port", guest: 3306, host: 3306
  config.vm.network "forwarded_port", guest: 8025, host: 8025

  config.vm.provider "virtualbox" do |v|
    v.memory = 2048
    v.customize ["modifyvm", :id, "--uartmode1", "disconnected"]
  end

  if use_nfs
    config.vm.synced_folder "./", "/vagrant", id: "vagrant-root",
      type: "nfs",
      mount_options: ["rw", "vers=3", "tcp", "fsc", "actimeo=1"]
  else
    config.vm.synced_folder "./", "/vagrant", id: "vagrant-root",
      owner: "vagrant",
      group: "www-data",
      mount_options: ["dmode=775,fmode=777"]
  end

  config.vm.provision "shell", path: "provision.sh", args: ["#{details[:domain]}", "#{details[:webroot]}", "#{details[:timezone]}"]
  config.vm.provision "shell", privileged: false, run: "always", inline: <<-SHELL
    /usr/local/bin/mailhog &> /dev/null &
  SHELL
end
