Defcon XX Team LBS

NMAP-web

Requirements
============
	nmap
	php5.3


Nmap Requires Sudo
=============
For some of the nmap commands it requires you run them with elevated privileges. Until we have time for a better solution, will need to add your apache user to the sudoers file.

The solution to this is very simple, specially on Ubuntu. The Apache’s user www-data need to be granted privileges to execute certain applications using sudo.

1. Run the command sudo visudo

2. At the end of the file, add the following

www-data ALL=NOPASSWD: /usr/bin/nmap


