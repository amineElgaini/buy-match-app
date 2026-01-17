# Buy Match

A simple PHP MVC web application for managing sports matches, tickets, and user profiles with role-based access control (Admin, Organizer, and regular users).

## Table of Contents

* [Features](#features)
* [Requirements](#requirements)
* [Installation](#installation)
* [Configuration](#configuration)
* [Routes](#routes)
* [Usage](#usage)
* [Contributing](#contributing)
* [License](#license)

## Features

* User authentication (register, login, logout)
* Role-based access:

  * Admin: Manage users, approve or refuse matches, access dashboard
  * Organizer: Access organizer dashboard
  * Authenticated users: View profile, buy tickets, comment on matches
* Match management: Create, list, view matches
* Ticket purchasing
* Commenting on matches
* Custom error handling with `AppException` and `ErrorHandler`

## Requirements

* PHP >= 8.0
* Composer
* MySQL / PostgreSQL (configurable via `.env`)
* Web server (Apache/Nginx)

## Installation

1. Clone the repository:

```bash
git clone https://github.com/amineElgaini/buy-match-app.git
cd buy-app
```

2. Install dependencies using Composer:

```bash
composer install
```

3. Configure your `.env` file (see [Configuration](#configuration))
4. Make sure the `sessions` directory is writable if using `session_start()`

## Configuration

1. Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

2. Set your database credentials and other environment variables:

```env
DB_HOST=localhost
DB_NAME=buy_match
DB_USER=root
DB_PASS=password
```

## Project Structure

```
/app
  /Controllers
  /Core
  /Models
/public
  index.php       <- Entry point
/vendor
.env             <- Environment variables
```

### Core Components

* `Router.php` → Handles all GET/POST routes and middleware
* `AppException.php` → Custom exception handling
* `ErrorHandler.php` → Registers global error handler
* `View.php` → Simple view rendering

## Routes

### Public

| Method | URI       | Controller#Action           |
| ------ | --------- | --------------------------- |
| GET    | /         | HomeController@index        |
| GET    | /login    | UserController@showLogin    |
| POST   | /login    | UserController@login        |
| GET    | /register | UserController@showRegister |
| POST   | /register | UserController@register     |
| GET    | /matches  | MatchController@index       |

### Authenticated Users

| Method | URI           | Controller#Action            |
| ------ | ------------- | ---------------------------- |
| GET    | /logout       | UserController@logout        |
| GET    | /profile      | UserController@profile       |
| POST   | /profile      | UserController@updateProfile |
| GET    | /matches/{id} | MatchController@show         |
| POST   | /tickets      | TicketController@store       |
| POST   | /{id}/comment | CommentController@store      |

### Admin

| Method | URI                         | Controller#Action            |
| ------ | --------------------------- | ---------------------------- |
| GET    | /admin-dashboard            | AdminController@dashboard    |
| POST   | /admin/matches/{id}/approve | AdminController@approveMatch |
| POST   | /admin/matches/{id}/refuse  | AdminController@refuseMatch  |
| GET    | /admin/users                | AdminController@users        |
| POST   | /admin/users/disable        | AdminController@disableUser  |
| POST   | /admin/users/enable         | AdminController@enableUser   |

### Organizer

| Method | URI                  | Controller#Action             |
| ------ | -------------------- | ----------------------------- |
| GET    | /organizer-dashboard | OrganizerController@dashboard |

## Usage

1. Start a local PHP server:

```bash
php -S localhost:8000 -t public
```

2. Open `http://localhost:8000` in your browser
3. Register a new user, or log in as an admin to manage matches and users.
