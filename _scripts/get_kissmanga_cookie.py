import requests
import cookielib

cj = cookielib.MozillaCookieJar()

from ghost import Ghost
ghost = Ghost()
with ghost.start(display=False, download_images=False, wait_timeout=10, user_agent='Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2824.0 Safari/537.36') as session:
	page, extra_resources = session.open("http://kissmanga.com/")
	# Wait for random value to be set
	session.wait_for_selector('#jschl-answer[value]', timeout=8)

	# Stop JS on the redirected page
	session.javascript_enabled = False
	#Wait for redirected page to load
	session.wait_for_selector('#containerRoot')
	session.page = None #https://github.com/jeanphix/Ghost.py/issues/186#issuecomment-148854236
	# for cookie in session.cookies:
		# if cookie.name() == 'cf_clearance':
			# print(cookie.value())
			# break
	session.save_cookies(cj)

	cj.save('/var/www/tracker.codeanimu.net/dev/public_html/_scripts/cookiejar', True, True)