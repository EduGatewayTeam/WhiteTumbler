from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions
import time

BASE_URL = 'http://localhost:8080'
DEFAULT_INTERACTIVE_TIMEOUT = 60

def find_interactive_element(driver, by, selector, timeout=DEFAULT_INTERACTIVE_TIMEOUT):
	"""Wait for appearence of element in dom within timeout and then return element or raise
	exception
	"""
	try:
		WebDriverWait(driver, timeout).until(expected_conditions.presence_of_element_located((by, selector)))
		return driver.find_element(by, selector)
	except:
		raise Exception("Can't find interactive element {}".format(str((by, selector))))

driver = webdriver.Chrome()
driver.get(BASE_URL)

# Auth
find_interactive_element(driver, By.LINK_TEXT, 'Lecturer').click()
find_interactive_element(driver, By.ID, 'username').send_keys('test3')
find_interactive_element(driver, By.ID, 'password').send_keys('test123')
find_interactive_element(driver, By.ID, 'kc-login').click()

# Create room
find_interactive_element(driver, By.XPATH, "//button[@data-bs-target='#add-room-modal']").click()
driver.implicitly_wait(5)
find_interactive_element(driver, By.ID, 'inputRoomCreateName').send_keys('Test room')
find_interactive_element(driver, By.XPATH, "//button[.//span[contains(text(), 'Create')]]").click()

# Join room
find_interactive_element(driver, By.LINK_TEXT, 'Test room').click()
find_interactive_element(driver, By.LINK_TEXT, 'Start room').click()

driver.implicitly_wait(15)

# Back
driver.get(BASE_URL)

# Remove room
find_interactive_element(driver, By.XPATH, "//div[./div[./a[contains(text(), 'Test room')]]]/div/div/button/i[@class='fa fa-trash']").click()
time.sleep(5)
find_interactive_element(driver, By.XPATH, "//button[.//span[contains(text(), 'Delete room')]]").click()
# find_interactive_element(driver, By.CLASS_NAME, 'btn-close').click()

time.sleep(20)

driver.close()
