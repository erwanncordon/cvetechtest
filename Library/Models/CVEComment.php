<?php

namespace Cve\Models;

class CVEComment implements DataInterface
{
    /**
     * @var string $author
     */
    public $author;

    /**
     * @var string $user_comment
     */
    public $user_comment;

    /**
     * @return array
     */
    public function getData() {
        return array(
            'author' => $this->author,
            'user_comment' => $this->user_comment
        );
    }
}