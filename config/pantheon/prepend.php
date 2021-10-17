<?php

define('PANTHEON_SITE', 'pantheon-site');
define('PANTHEON_ENVIRONMENT', 'ci');

define('PANTHEON_DATABASE_HOST', getenv('DB_HOST'));
define('PANTHEON_DATABASE_PORT', getenv('DB_PORT'));
define('PANTHEON_DATABASE_USERNAME', getenv('DB_USER'));
define('PANTHEON_DATABASE_PASSWORD', getenv('DB_PASS'));
define('PANTHEON_DATABASE_DATABASE', getenv('DB_NAME'));
define('PANTHEON_DATABASE_PREFIX', '');

define('PANTHEON_REDIS_HOST', getenv('CACHE_SERVER'));
define('PANTHEON_REDIS_PORT', getenv('CACHE_PORT'));
define('PANTHEON_REDIS_PASSWORD', getenv('CACHE_PASSWORD'));
define('PANTHEON_INDEX_HOST', getenv('INDEX_HOST'));
define('PANTHEON_INDEX_PORT', getenv('INDEX_PORT'));
define('DRUPAL_HASH_SALT', 'hashsalt');

$_ENV['PANTHEON_SITE'] = PANTHEON_SITE;
$_ENV['PANTHEON_ENVIRONMENT'] = PANTHEON_ENVIRONMENT;
$_ENV['DRUPAL_HASH_SALT'] = DRUPAL_HASH_SALT;
$_ENV['DB_HOST'] = PANTHEON_DATABASE_HOST;
$_ENV['DB_PORT'] = PANTHEON_DATABASE_PORT;
$_ENV['DB_USER'] = PANTHEON_DATABASE_USERNAME;
$_ENV['DB_PASSWORD'] = PANTHEON_DATABASE_PASSWORD;
$_ENV['DB_NAME'] = PANTHEON_DATABASE_DATABASE;
$_ENV['DB_PREFIX'] = PANTHEON_DATABASE_PREFIX;
$_ENV['CACHE_HOST'] = PANTHEON_REDIS_HOST;
$_ENV['CACHE_PORT'] = PANTHEON_REDIS_PORT;
$_ENV['CACHE_PASSWORD'] = PANTHEON_REDIS_PASSWORD;
$_ENV['PANTHEON_INDEX_PORT'] = PANTHEON_INDEX_PORT;
$_ENV['PANTHEON_INDEX_HOST'] = PANTHEON_INDEX_HOST;

$_ENV['AUTH_KEY'] = 'auth_key';
$_ENV['SECURE_AUTH_KEY'] = 'secure_auth_key';
$_ENV['LOGGED_IN_KEY'] = 'logged_in_key';
$_ENV['AUTH_SALT'] = 'auth_salt';
$_ENV['SECURE_AUTH_SALT'] = 'secure_auth_salt';
$_ENV['LOGGED_IN_SALT'] = 'logged_in_salt';
$_ENV['NONCE_SALT'] = 'nonce_salt';

// System paths
putenv('PATH=/usr/local/bin:/bin:/usr/bin:/srv/bin');

$settings = array (
  'conf' => array (
    'pressflow_smart_start' => true,
    'pantheon_site_uuid' => PANTHEON_SITE,
    'pantheon_environment' => PANTHEON_ENVIRONMENT,
    'pantheon_index_host' => PANTHEON_INDEX_HOST,
    'pantheon_index_port' => PANTHEON_INDEX_PORT,
    'redis_client_host' => PANTHEON_REDIS_HOST,
    'redis_client_port' => PANTHEON_REDIS_PORT,
    'redis_client_password' => PANTHEON_REDIS_PASSWORD,
    'file_public_path' => 'sites/default/files',
    'file_private_path' => 'sites/default/files/private',
    'file_directory_path' => 'sites/default/files',
    'css_gzip_compression' => false,
    'js_gzip_compression' => false,
    'page_compression' => false,
    'drupal_hash_salt' => DRUPAL_HASH_SALT,
    'config_directory_name' => 'config',
    'file_chmod_directory' => 0777,
    'file_chmod_file' => 0666
  ),
  'databases' => array (
    'default' => array (
      'default' => array (
        'host' => PANTHEON_DATABASE_HOST,
        'port' => PANTHEON_DATABASE_PORT,
        'username' => PANTHEON_DATABASE_USERNAME,
        'password' => PANTHEON_DATABASE_PASSWORD,
        'database' => PANTHEON_DATABASE_DATABASE,
        'driver' => 'mysql',
        'prefix' => '',
      ),
    ),
  ),
);

// Legacy Drupal Settings Block
$_SERVER['PRESSFLOW_SETTINGS'] = json_encode($settings);
