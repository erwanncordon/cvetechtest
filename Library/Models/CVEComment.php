<?php

namespace Cve\Models;

class CVEComment implements DataInterface
{
    public $author;
    public $user_comment;

    public function getData() {
        return array(
            'author' => $this->author,
            'user_comment' => $this->user_comment
        );
    }
}