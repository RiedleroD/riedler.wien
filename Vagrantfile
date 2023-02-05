# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  #TODO: update to newer debian
  config.vm.box = "debian/jessie64"
  #NOTE: needs vagrant-hostsupdater plugin
  config.vm.hostname = "riedler.local"
  # presumably also works with different providers
  config.vm.provider :libvirt do |libvirt|
    libvirt.cpus=4
    libvirt.memory=2048
  end
  #TODO: add provision script
end