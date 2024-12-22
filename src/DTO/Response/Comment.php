<?php

namespace Stereoqweex\TestDromPackage\DTO\Response;

class Comment
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $text,
    ) {
    }
}