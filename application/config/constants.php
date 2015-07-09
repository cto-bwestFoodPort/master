<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/**
 * Register Errors
 */
define('REG_USERNAME_USED', 'Username already in use');
define('REG_USERNAME_OK', 'This username is available');
define('REG_EMAIL', 'Email already in use');
define('USER_REG_PASS', 'Successfully registered');
define('USER_REG_FAIL', 'Registration failed');
/**
 * News Alerts
 */
define('NEWS_QUEUED', "News item queued for approval");

/**
 * Login Errors
 */
define('INVALID_LOGIN_ERR', 1);
define('INVALID_LOGIN_MSG', 'Invalid Login, please try again!');

/**
* Form Errors
*
**/
define('EMP_ADD_ERR', "Adding employee failed, please try again.");
define('EMP_ADD_SUCC', "Employee successfully added.");

/**
 * Guest ID
 */
define('GUEST_ID', 1);

/**
* Prices
**/
define('FOURTY_FIVE_PERCENT', 0.45);
define('THIRTY_FIVE_PERCENT', 0.35);
define('THIRTY_PERCENT', 0.30);
define('TWENTY_FIVE_PERCENT', 0.25);
define('DRIVER_BASE_RATE', 10.00);

define('MULTI_REST_FEE', 3.00);
define('MILEAGE_FEE', 0.50);
/**
* API Keys
**/
define('TAX_APIKEY', 'xWawGyC9bq9SeW11QfCM5nyAczr9asPNh87RtjTrApFvd6yQlzC1AbHvlK0iaxTF6DF3ontwRW6/p6UDq7ljwA==');
define('GOOGLE_APIKEY', 'AIzaSyC7L1rknBFfEsrUW0nQdEsU23Id68sWBsE');

/**
* Tax API URL
**/
define('TAX_URL', 'https://taxrates.api.avalara.com/postal');

/**
* Distance URL
**/

define('DISTANCE_URL', 'https://maps.googleapis.com/maps/api/distancematrix/');

/**
*PayPal credentials
**/
define('TEST_USERNAME', 'cto-brian.west-facilitator_api1.foodport.com');
define('TEST_PASS', 'E5C7596PVKFK9LDM');
define('TEST_SIG', 'AYgPOmvbiT6PLeUo4F.GW85q9kPgAFX.RvMX8XeVMLnzpjQ3j0xEi1J4');
/* End of file constants.php */
/* Location: ./application/config/constants.php */