# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "CentOS7_Practico"

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  # config.vm.box_check_update = false

  # Redireccion del puerto apache en la VM para su uso local sobre el puerto 81 del anfitrion
    config.vm.network :forwarded_port, host: 8080, guest: 80

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  # config.vm.network "private_network", ip: "192.168.33.10"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder "../data", "/vagrant_data"

  # Provider-specific configuration so you can fine-tune various
  # backing providers for Vagrant. These expose provider-specific options.
  # Example for VirtualBox:
  #
  # config.vm.provider "virtualbox" do |vb|
  #   # Display the VirtualBox GUI when booting the machine
  #   vb.gui = true
  #
  #   # Customize the amount of memory on the VM:
  #   vb.memory = "1024"
  # end
  #
  # View the documentation for the provider you are using for more
  # information on available options.

  config.vm.provision "shell", inline: <<-SHELL
	echo "    /\\  _  _ _   . _. _  _  _  _ _ . _  _ _|_ _    "
	echo "   /~~\\|_)| (_)\\/|_\\|(_)| |(_|| | ||(/_| | | (_)   "
	echo "       |                                           "
	echo "---------------------------------------------------"

	#Instalacion de Apache
	sudo yum -y install httpd
	sudo systemctl start httpd.service
	sudo systemctl enable httpd.service

	#Instalacion de PHP
	sudo yum -y install php php-mysql php-gd php-ldap php-odbc php-pear php-xml php-xmlrpc php-mbstring php-snmp php-soap curl curl-devel
	sudo systemctl restart httpd.service
	
	sudo mv /var/www/html /var/www/html_old
	sudo ln -s /vagrant /var/www/html
	
	#Instalacion de MariaDB
	sudo yum -y install mariadb-server mariadb
	sudo systemctl start mariadb.service
	sudo systemctl enable mariadb.service
	
	#Instalacion de la base de datos
	mysql --user=root -e "CREATE DATABASE IF NOT EXISTS practico;"	
	mysql -h "localhost" --user=root --database=practico < "ins/sql/practico.mysql"
	mysql --user=root -e "FLUSH PRIVILEGES;"		
	mysql --user=root -e "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('mypass');"

	echo "-------------- FIN APROVISIONAMIENTO --------------"
  SHELL

end
