#!/bin/bash

#FIXME: All these scripts should affect both prod & dev

#Get KissManga CookieLib
#FIXME: TMP DISABLED, SEE #64
#sudo python /var/www/trackr.moe/trackr/public_html/_scripts/get_kissmanga_cookie.py

# Fix user permissions
sudo chown www-data:www-data /var/www/trackr.moe/ --recursive

#Run update
sudo -u www-data php /var/www/trackr.moe/trackr/public_html/public/index.php admin/update_titles
