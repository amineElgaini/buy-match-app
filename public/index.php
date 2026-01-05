<?php
session_start();

require_once '../app/Router.php';

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
require_once '../app/controllers/UserController.php';
require_once '../app/controllers/MatchController.php';
require_once '../app/controllers/TicketController.php';
require_once '../app/controllers/CommentController.php';
require_once '../app/controllers/AdminController.php';

$router = new Router();

/*
|--------------------------------------------------------------------------
| AUTH / USER ROUTES
|--------------------------------------------------------------------------
*/
$router->get('/login', ['UserController', 'showLogin'], ['guest']);
$router->post('/login', ['UserController', 'login'], ['guest']);

$router->get('/register', ['UserController', 'showRegister'], ['guest']);
$router->post('/register', ['UserController', 'register'], ['guest']);

$router->get('/logout', ['UserController', 'logout'], ['auth']);

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES (User / Organizer / Admin)
|--------------------------------------------------------------------------
*/
$router->get('/profile', ['UserController', 'profile'], ['auth']);
$router->post('/profile', ['UserController', 'updateProfile'], ['auth']);

/*
|--------------------------------------------------------------------------
| MATCH ROUTES (Visitor)
|--------------------------------------------------------------------------
*/
$router->get('/', ['MatchController', 'index']);
$router->get('/matches', ['MatchController', 'index']);
$router->get('/matches/{id}', ['MatchController', 'show']);

/*
|--------------------------------------------------------------------------
| MATCH ROUTES (Organizer)
|--------------------------------------------------------------------------
*/
$router->get(
    '/organizer/matches/create',
    ['MatchController', 'create'],
    ['auth', 'role:organizer']
);

$router->post(
    '/organizer/matches',
    ['MatchController', 'store'],
    ['auth', 'role:organizer']
);

/*
|--------------------------------------------------------------------------
| TICKET ROUTES (User)
|--------------------------------------------------------------------------
*/
$router->post(
    '/tickets/buy',
    ['TicketController', 'buy'],
    ['auth', 'role:user']
);

$router->get(
    '/tickets/my',
    ['TicketController', 'myTickets'],
    ['auth', 'role:user']
);

/*
|--------------------------------------------------------------------------
| COMMENT ROUTES (After Match)
|--------------------------------------------------------------------------
*/
$router->post(
    '/comments',
    ['CommentController', 'store'],
    ['auth', 'role:user']
);

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
$router->get(
    '/admin/users',
    ['AdminController', 'users'],
    ['auth', 'role:admin']
);

$router->post(
    '/admin/users/disable',
    ['AdminController', 'disableUser'],
    ['auth', 'role:admin']
);

$router->get(
    '/admin/matches',
    ['AdminController', 'matches'],
    ['auth', 'role:admin']
);

$router->post(
    '/admin/matches/approve',
    ['AdminController', 'approveMatch'],
    ['auth', 'role:admin']
);

$router->post(
    '/admin/matches/refuse',
    ['AdminController', 'refuseMatch'],
    ['auth', 'role:admin']
);

/*
|--------------------------------------------------------------------------
| Dispatch
|--------------------------------------------------------------------------
*/
$router->dispatch();
