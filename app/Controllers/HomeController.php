<?php

class HomeController
{
    public function index()
    {
        header('Location: /matches');
        exit;
    }
}
