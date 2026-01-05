<?php

require_once __DIR__ . '/../models/MatchGame.php';

class MatchController
{
    public function index()
    {
        // fetch approved matches
        require '../app/views/matches/index.php';
    }

    public function show($id)
    {
        // fetch match by id
        require '../app/views/matches/show.php';
    }

    public function create()
    {
        require '../app/views/matches/create.php';
    }

    public function store()
    {
        $match = new MatchGame();
        $match->organizer_id = $_SESSION['user']['id'];
        $match->team1_name = $_POST['team1'];
        $match->team2_name = $_POST['team2'];
        $match->date_time = $_POST['date_time'];
        $match->location = $_POST['location'];
        $match->max_seats = $_POST['max_seats'];
        $match->status = 'pending';

        $match->save();

        header('Location: /');
    }
}
