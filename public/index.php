<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . './../app/Core/ErrorHandler.php';
ErrorHandler::register();
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

require_once __DIR__ . '/../app/Core/View.php';
require_once __DIR__ . './../app/Core/AppException.php';
require_once __DIR__ . './../app/Router.php';

$router = new Router();


$router->get('/', ['HomeController', 'index']);

$router->get('/login', ['UserController', 'showLogin']);
$router->post('/login', ['UserController', 'login']);

$router->get('/register', ['UserController', 'showRegister']);
$router->post('/register', ['UserController', 'register']);

$router->get('/logout', ['UserController', 'logout'], ['auth']);

$router->get('/profile', ['UserController', 'profile'], ['auth']);
$router->post('/profile', ['UserController', 'updateProfile'], ['auth']);

$router->get('/matches', ['MatchController', 'index']);

$router->get('/matches/{id}', ['MatchController', 'show'], ['auth']);
$router->post('/tickets', ['TicketController', 'store'], ['auth']);
$router->post('/{id}/comment', ['CommentController', 'store'], ['auth']);

$router->get('/match-form', ['MatchController', 'createMatch']);
$router->post('/match-form', ['MatchController', 'storeMatch']);

$router->get('/admin-dashboard', ['AdminController', 'dashboard'], ['auth', 'role:admin']);
$router->get('/organizer-dashboard', ['OrganizerController', 'dashboard'], ['role:organizer']);

$router->post('/admin/matches/{id}/approve', ['AdminController', 'approveMatch'], ['auth', 'role:admin']);
$router->post('/admin/matches/{id}/refuse', ['AdminController', 'refuseMatch'], ['auth', 'role:admin']);


$router->get('/admin/users', ['AdminController', 'users'], ['auth', 'role:admin']);
$router->post('/admin/users/disable', ['AdminController', 'disableUser'], ['auth', 'role:admin']);
$router->post('/admin/users/enable', ['AdminController', 'enableUser'], ['auth', 'role:admin']);


$router->dispatch();
