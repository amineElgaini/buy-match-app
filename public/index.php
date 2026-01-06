<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once '../app/Router.php';

require_once '../app/controllers/UserController.php';
// require_once '../app/controllers/MatchController.php';
// require_once '../app/controllers/TicketController.php';
// require_once '../app/controllers/CommentController.php';
// require_once '../app/controllers/AdminController.php';

$router = new Router();


$router->get('/', ['HomeController', 'index']);

$router->get('/login', ['UserController', 'showLogin']);
$router->post('/login', ['UserController', 'login']);

$router->get('/register', ['UserController', 'showRegister']);
$router->post('/register', ['UserController', 'register']);

$router->get('/logout', ['UserController', 'logout'], ['auth']);

$router->get('/profile', ['UserController', 'profile'], ['auth', 'role:user']);
$router->post('/profile', ['UserController', 'updateProfile'], ['auth']);

$router->get('/matches', ['MatchController', 'index']);

$router->get('/matches/{id}', ['MatchController', 'show'], ['auth']);
$router->post('/tickets', ['TicketController', 'store'], ['auth']);

$router->get('/match-form', ['MatchController', 'createMatch']);
$router->post('/match-form', ['MatchController', 'storeMatch']);

$router->get('/admin-dashboard', ['AdminController', 'dashboard'], ['role:admin']);
$router->get('/organizer-dashboard', ['OrganizerController', 'dashboard'], ['role:organizer']);


// /*
// |--------------------------------------------------------------------------
// | MATCH ROUTES (Organizer)
// |--------------------------------------------------------------------------
// */
// $router->get(
//     '/organizer/matches/create',
//     ['MatchController', 'create'],
//     ['auth', 'role:organizer']
// );

// $router->post(
//     '/organizer/matches',
//     ['MatchController', 'store'],
//     ['auth', 'role:organizer']
// );

// /*
// |--------------------------------------------------------------------------
// | TICKET ROUTES (User)
// |--------------------------------------------------------------------------
// */
// $router->post(
//     '/tickets/buy',
//     ['TicketController', 'buy'],
//     ['auth', 'role:user']
// );

// $router->get(
//     '/tickets/my',
//     ['TicketController', 'myTickets'],
//     ['auth', 'role:user']
// );

// /*
// |--------------------------------------------------------------------------
// | COMMENT ROUTES (After Match)
// |--------------------------------------------------------------------------
// */
// $router->post(
//     '/comments',
//     ['CommentController', 'store'],
//     ['auth', 'role:user']
// );

// /*
// |--------------------------------------------------------------------------
// | ADMIN ROUTES
// |--------------------------------------------------------------------------
// */
// $router->get(
//     '/admin/users',
//     ['AdminController', 'users'],
//     ['auth', 'role:admin']
// );

// $router->post(
//     '/admin/users/disable',
//     ['AdminController', 'disableUser'],
//     ['auth', 'role:admin']
// );

// $router->get(
//     '/admin/matches',
//     ['AdminController', 'matches'],
//     ['auth', 'role:admin']
// );

// $router->post(
//     '/admin/matches/approve',
//     ['AdminController', 'approveMatch'],
//     ['auth', 'role:admin']
// );

// $router->post(
//     '/admin/matches/refuse',
//     ['AdminController', 'refuseMatch'],
//     ['auth', 'role:admin']
// );

/*
|--------------------------------------------------------------------------
| Dispatch
|--------------------------------------------------------------------------
*/
$router->dispatch();
