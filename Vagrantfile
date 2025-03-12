# -*- mode: ruby -*-
# vi: set ft=ruby :

$provision = <<SCRIPT

apt-get update
apt-get install nginx mariadb-server php php-mysql php-fpm -y

rm /etc/nginx/sites-available/default -f
cp /vagrant/nginxfile /etc/nginx/sites-available/default

sudo systemctl restart nginx

echo "don't forget to enable php error reporting in php.ini"

SCRIPT

# check for plugins
required_plugins = ['vagrant-hostsupdater', 'vagrant-libvirt']
plugins_to_install = required_plugins.select { |plugin| not Vagrant.has_plugin? plugin }
if not plugins_to_install.empty?
  abort "You need to install these vagrant plugins: " + plugins_to_install.to_s
end

Vagrant.configure("2") do |config|
  config.vm.box = "debian/bookworm64"
  config.vm.hostname = "riedler.local"
  config.vm.provider :libvirt do |libvirt|
    libvirt.cpus=4
    libvirt.memory=2048
    libvirt.graphics_type='spice' # otherwise it complains about missing audio system…?
  end
  config.vm.provision :shell, inline: $provision, privileged: true, reset: true
end