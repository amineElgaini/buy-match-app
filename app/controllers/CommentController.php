<?php

require_once __DIR__ . '/../models/Comment.php';

class CommentController
{
    public function store()
    {
        $comment = new Comment();
        $comment->user_id = $_SESSION['user']['id'];
        $comment->match_id = $_POST['match_id'];
        $comment->rating = $_POST['rating'];
        $comment->comment = $_POST['comment'];

        $comment->save();

        header('Location: /matches/' . $_POST['match_id']);
    }
}
