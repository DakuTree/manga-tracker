import os
from time import sleep

from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

root_dir = os.path.abspath(__file__ + "/../../../")

def main():
	driver = setupDriver()

	installUserscript(driver) #FIXME: Preferably we'd just pre-install it, but I can't figure out how to without loading the entire user_dir, which sadly causes issues

	input("PRESS ENTER TO CONTINUE.")
	driver.quit()

def setupDriver():
	options = webdriver.ChromeOptions()

	# options.add_argument('headless')
	options.add_argument('window-size=800,600')
	options.add_argument('homepage=about:blank')
	# options.add_argument("user-data-dir=./user_dir") #NOTE: This causes slowdown and various other bugs sadly
	options.add_extension('tampermonkey_4_3_6.crx') #use http://crxextractor.com/ to safely download off store
	
	# prefs = {"homepage" : "about:blank"}
	# options.add_experimental_option("prefs", prefs)

	driver = webdriver.Chrome(chrome_options=options)

	sleep(2) #TODO: Is there a better way to wait for tab to exist?
	driver.switch_to.window(driver.window_handles[-1]) #Switch to TamperMonkey changelog tab
	assert "Tampermonkey" in driver.title
	driver.close() #Close TamperMonkey tab
	driver.switch_to.window(driver.window_handles[-1]) #Switch back to original tab
	assert "data:," in driver.current_url
	
	return driver

def installUserscript(driver):
	driver.get("file:///" + root_dir + "/public/userscripts/manga-tracker.user.js")

	#The above .get is a bit weird due to chrome weirdness with extensions
	sleep(2)
	driver.switch_to.window(driver.window_handles[-1]) #Switch to install tab

	try:
		element = WebDriverWait(driver, 10).until(
			EC.presence_of_element_located((By.CLASS_NAME, "install"))
		)
		element.click()

		#Selenium yet again being weird and not automatically switching window properly.
		sleep(2)
		driver.switch_to.window(driver.window_handles[-1]) #Switch to install tab
	finally:
		print(driver.current_url)
		# driver.quit()

	return

# def downloadExt():
	# ext_url = 'https://clients2.google.com/service/update2/crx?response=redirect&prodversion=49.0&x=id=dhdgffkkebhmkfjojejmpbldmpobfkfo&installsource=ondemand&uc'
	
if __name__ == '__main__':
    main()
