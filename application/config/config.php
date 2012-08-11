<?php 

///By peejay
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];


$config['base_url'] = $uri; // Base URL including trailing slash (e.g. http://localhost/)

$config['default_controller'] = 'user'; // Default controller to load
$config['error_controller'] = 'error'; // Controller used for errors (e.g. 404, 500 etc)

$config['db_host'] = 'localhost'; // Database host (e.g. localhost)
$config['db_name'] = 'coderepos'; // Database name
$config['db_username'] = 'root'; // Database username
$config['db_password'] = ''; // Database password

?>