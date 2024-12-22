<?php

namespace Stereoqweex\TestDromPackage\DTO\Request;

class PostCommentDTO
{
    public string $name;

    public string $text;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'text' => $this->text,
        ];
    }
}