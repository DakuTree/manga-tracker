#!/bin/bash

#Get KissManga CookieLib
sudo python /var/www/tracker.codeanimu.net/dev/public_html/_scripts/get_kissmanga_cookie.py
sudo chown www-data:www-data ./ --recursive

#Run update
php /var/www/tracker.codeanimu.net/dev/public_html/public/index.php admin/update_titles