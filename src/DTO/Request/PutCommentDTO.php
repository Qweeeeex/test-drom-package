<?php

namespace Stereoqweex\TestDromPackage\DTO\Request;

class PutCommentDTO
{
    public int $id;
    public ?string $name = null;

    public ?string $text = null;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'text' => $this->text,
        ];
    }
}