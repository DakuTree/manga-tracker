#!/bin/bash

#Get KissManga CookieLib
sudo python /var/www/trackr.moe/dev/public_html/_scripts/get_kissmanga_cookie.py

# Fix user permissions
sudo chown www-data:www-data /var/www/trackr.moe/ --recursive

#Test sites
#TODO: This should be site specific
RESULT=$(php -dxdebug.profiler_enable=off /var/www/trackr.moe/dev/public_html/vendor/phpunit/phpunit/phpunit --bootstrap /var/www/trackr.moe/dev/public_html/application/tests/Bootstrap.php --configuration /var/www/trackr.moe/dev/public_html/application/tests/phpunit.xml Site_Model_test /var/www/trackr.moe/dev/public_html/application/tests/models/Sites_Model_test.php)

if [[ ! $RESULT =~ "FAILURES" ]]; then
	mailx -s "Trackr.moe DailyTest: SUCCESS" < /dev/null "email"
else
	mailx -s "Trackr.moe DailyTest: FAILURE" < $RESULT "email"
fi
