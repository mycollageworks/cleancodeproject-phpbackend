<?php

namespace App\Models;

class Note
{
    public function __construct(
        public int $id,
        public string $date,
        public string $content,
        public \DateTime $createdAt,
        public \DateTime $updatedAt
    ) {}
}
