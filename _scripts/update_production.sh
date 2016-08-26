#!/bin/bash

if ! [ $(id -u) = 0 ]; then
   echo "This script must be run as root"
   exit 1
fi

if [ ! -f config.cfg ]; then
    echo "Missing config.cfg!"
    exit 1
fi

. config.cfg

#Make sure we're running on prod
dirArr=(${PWD//\// })
if [ "${dirArr[3]}" == 'dev' ]; then
	#We're running on prod, check if arg exists
	if [ -n "$1" ]; then
		tagName="$1";
		tarURL="https://api.github.com/repos/DakuTree/manga-tracker/tarball/$tagName"
#		tarURL=$(curl -s "https://api.github.com/repos/DakuTree/manga-tracker/tags?access_token=$accessToken"\
#		 | python -mjson.tool \
#		 | grep tarball_url \
#		 | sed -E 's/^[ ]+"tarball_url": "(.*?)",$/\1/');
		tarURL+="?access_token=$accessToken";

		curl -Lo /tmp/tracker.tar.gz "$tarURL"
		if [ $(stat --printf="%s" /tmp/tracker.tar.gz) == '0' ]; then
			echo "BAD URL";
		else
			#TODO: Turn on update mode, wait 5 minutes~
			sudo tar -zxvf /tmp/tracker.tar.gz -C /var/www/tracker.codeanimu.net/tracker/public_html/ --strip 1 --owner="$(id -u www-data)" --group="$(id -g www-data)"
			sudo chown www-data:www-data /var/www/tracker.codeanimu.net/ -R
			#TODO: Update composer
			#TODO: Run migration
			#TODO: Run title update
			#TODO: Turn off update mode.
			echo "Done updating!"
		fi;
	else
		echo "Script missing argument";
	fi
else
	echo "Script isn't running on dev!";
fi
