<?php

require_once('../vendor/autoload.php');

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

// Selenium server URL
$host = 'http://localhost:9515'; // Make sure Selenium server is running

// Start Chrome
$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());

try {
    // 1. Go to your login page
    $driver->get('http://localhost/placementportal/auth/login.php');

    // 2. Fill in login form (adjust selectors if needed)
    $driver->findElement(WebDriverBy::name('email'))->sendKeys('admin@gmail.com');
    $driver->findElement(WebDriverBy::name('password'))->sendKeys('Admin@123');

    // 3. Submit the form
    $driver->findElement(WebDriverBy::id('loginBtn'))->click();

    // 4. Wait for redirect or dashboard element
    try {
        $driver->wait(10, 500)->until(
            WebDriverExpectedCondition::urlContains('account/dashboard.php')
        );
    } catch (\Facebook\WebDriver\Exception\TimeOutException $e) {
        // If URL did not change, wait for the "Welcome" element
        $driver->wait(5, 500)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath("//*[contains(text(),'Welcome')]"))
        );
    }

    // 5. Check if login was successful
    $currentUrl = $driver->getCurrentURL();
    echo "After login, URL is: $currentUrl\n";

    if (strpos($currentUrl, 'account/dashboard.php') !== false || strpos($driver->getPageSource(), 'Welcome') !== false) {
        echo "✅ Login test passed.\n";
    } else {
        echo "❌ Login test failed.\n";
    }
} catch (Exception $e) {
    echo "❌ Test failed with exception: " . $e->getMessage() . "\n";
} finally {
    // $driver->quit();
}
