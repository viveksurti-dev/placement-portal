<?php

require_once '../vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;

$host = 'http://localhost:9515';
$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());

$testCases = [
    [
        'email' => 'admin@gmail.com',
        'password' => 'Admin@123',
        'expected' => 'success',
        'description' => 'Valid login'
    ],
    [
        'email' => 'admin@gmail.com',
        'password' => 'wrongpassword',
        'expected' => 'failure',
        'description' => 'Wrong password'
    ],
    [
        'email' => 'invalid@gmail.com',
        'password' => 'Admin@123',
        'expected' => 'failure',
        'description' => 'Invalid email'
    ],
    [
        'email' => '',
        'password' => '',
        'expected' => 'failure',
        'description' => 'Empty fields'
    ],
    [
        'email' => 'admin',
        'password' => 'Admin@123',
        'expected' => 'failure',
        'description' => 'Invalid Email Format'
    ],
    [
        'email' => "<script>alert('xss')</script>",
        'password' => 'test',
        'expected' => 'failure',
        'description' => 'XSS attempt in email'
    ],
    [
        'email' => "' OR 1=1 --",
        'password' => 'anything',
        'expected' => 'failure',
        'description' => 'SQL Injection attempt'
    ],
    [
        'email' => 'admin@gmail.com',
        'password' => '',
        'expected' => 'failure',
        'description' => 'Empty password'
    ]
];


foreach ($testCases as $index => $testCase) {
    echo "Running Test Case #" . ($index + 1) . ": {$testCase['description']}... ";

    try {
        $driver->get('http://localhost/placementportal/auth/login.php');

        $driver->findElement(WebDriverBy::name('email'))->clear()->sendKeys($testCase['email']);
        $driver->findElement(WebDriverBy::name('password'))->clear()->sendKeys($testCase['password']);
        $driver->findElement(WebDriverBy::id('loginBtn'))->click();

        sleep(1);

        $currentUrl = $driver->getCurrentURL();
        $pageSource = $driver->getPageSource();

        $isSuccess = strpos($currentUrl, 'dashboard.php') !== false || strpos($pageSource, 'Welcome') !== false;

        if (($testCase['expected'] === 'success' && $isSuccess) ||
            ($testCase['expected'] === 'failure' && !$isSuccess)
        ) {
            echo "Passed\n";
        } else {
            echo "Failed\n";
        }
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "\n";
    }
}

$driver->quit();
