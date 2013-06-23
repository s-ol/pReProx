pReProx
=======

A Reverse-Proxy Service using netcat, bash and PHP.

Installation
------------
Compile newredir.c to newredir and set the owner/group to root:root. Then set the 'set uid' flag (chmod u+s newredir).
Change the login info right on top of admin.php to your liking (set SECRET to something random). Then log into the admin panel and add / remove the ports you want to use.
Also make sure the firewall forwards all used ports.

A setup script for [instantserver.io](http://instantserver.io) servers can be found [here](http://pastebin.com/raw.php?i=wVc0Yami)

Screenshots
-----------
![main page](http://i.imgur.com/fzsLJgh.png)
![binding](http://i.imgur.com/0qE6K0m.png)
![admin control panel](http://i.imgur.com/i6jxXwz.png)
