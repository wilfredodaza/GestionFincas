<?php namespace Config;
      use App\Controllers\RestResume;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('home');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'HomeController::home');

$routes->get('/chat', 'PruebaController::chat');
$routes->get('/datatable/(:segment)', 'PruebaController::datatable/$1');

$routes->post('/table', 'PruebaController::table');
$routes->get('/table', 'PruebaController::table');

$routes->post('prueba', 'PruebaController::index');

$routes->get('password', 'PasswordController::index');
$routes->post('password/updated', 'PasswordController::updated');

$routes->group('dashboard', function ($routes){
	$routes->get('', 'DashboardController::index');
	$routes->post('calendar', 'DashboardController::calendar');
	$routes->group('movements', function ($routes){
		$routes->get('(:segment)', 'MovementController::index/$1');
		// $routes->get('bills', 'MovementController::bills');
		$routes->get('data/(:segment)', 'MovementController::data/$1');
		$routes->get('new/(:segment)', 'MovementController::created/$1');
		$routes->get('edit/(:segment)', 'MovementController::edit/$1');
		
		$routes->post('store', 'MovementController::store');
		$routes->post('updated', 'MovementController::updated');
		$routes->post('state', 'MovementController::state');

		$routes->get('download/(:num)', 'MovementController::download/$1');
	});

	$routes->group('resource', function($routes){
		$routes->get('kardex/(:num)', 'ResourceController::index/$1');
		$routes->get('data/(:num)', 'ResourceController::data/$1');
	});

	$routes->group('productive_units', function($routes){
		$routes->get('(:num)', 'ProductiveUnitController::index/$1');
		$routes->get('data/(:num)', 'ProductiveUnitController::data/$1');
		$routes->post('save', 'ProductiveUnitController::save');
	});
});


$routes->get('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::register');
$routes->post('/create', 'AuthController::create');
$routes->get('/reset_password', 'AuthController::resetPassword');
$routes->post('/forgot_password', 'AuthController::forgotPassword');
$routes->post('/validation', 'AuthController::validation');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/about', 'DashboardController::about');
$routes->get('/perfile', 'UserController::perfile');
$routes->post('/update_photo', 'UserController::updatePhoto');
$routes->post('/update_user', 'UserController::updateUser');
$routes->post('/config/(:segment)', 'ConfigController::index/$1');
$routes->get('/config/(:segment)', 'ConfigController::index/$1');
$routes->post('/table/(:segment)', 'TableController::index/$1');
$routes->get('/table/(:segment)', 'TableController::index/$1');

$routes->post('/table/(:segment)/(:segment)', 'TableController::detail/$1/$2');
$routes->get('/table/(:segment)/(:segment)', 'TableController::detail/$1/$2');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
