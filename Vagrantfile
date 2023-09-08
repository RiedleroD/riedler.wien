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

Vagrant.configure("2") do |config|
  #TODO: update to newer debian
  config.vm.box = "debian/bookworm64"
  #NOTE: needs vagrant-hostsupdater plugin
  config.vm.hostname = "riedler.local"
  # presumably also works with different providers
  config.vm.provider :libvirt do |libvirt|
    libvirt.cpus=4
    libvirt.memory=2048
    libvirt.graphics_type='spice' # otherwise it complains about missing audio system…?
  end
  config.vm.provision :shell, inline: $provision, privileged: true, reset: true
end