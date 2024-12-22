<?php

namespace Stereoqweex\TestDromPackage\Exception;

use Exception;

class CommentNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Комментарий не найден', 404);
    }
}