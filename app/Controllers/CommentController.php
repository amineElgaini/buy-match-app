<?php
require_once __DIR__ . '/../Models/Comment.php';

class CommentController
{
    private Comment $comment;

    public function __construct()
    {
        $this->comment  = new Comment();
    }

    public function store($match_id)
    {
        $this->comment->user_id = $_SESSION['user_id'];
        $this->comment->match_id = $match_id;
        $this->comment->rating = $_POST['rating'];
        $this->comment->comment = $_POST['comment'];

        $this->comment->save();

        header('Location: /buy-match/profile');
        exit;
    }
}
