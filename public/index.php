<?php

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */
$minPhpVersion = '8.3';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    header('HTTP/1.1 503 Service Unavailable', true, 503);
    echo 'Your PHP version must be ' . $minPhpVersion . ' or higher. '
       . 'Current version: ' . PHP_VERSION;
    exit(1);
}

/*
 *---------------------------------------------------------------
 * DEFINE FRONT CONTROLLER PATH
 *---------------------------------------------------------------
 */
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * ERROR REPORTING (solo para debug)
 *---------------------------------------------------------------
 * Puedes quitar esto cuando todo funcione
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/*
 *---------------------------------------------------------------
 * LOAD PATHS CONFIG
 *---------------------------------------------------------------
 */
require FCPATH . '../app/Config/Paths.php';

$paths = new Config\Paths();

/*
 *---------------------------------------------------------------
 * BOOTSTRAP CODEIGNITER (CI 4.5+)
 *---------------------------------------------------------------
 * ⚠️ NO usar system/bootstrap.php (obsoleto)
 */
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'Boot.php';

exit(CodeIgniter\Boot::bootWeb($paths));
