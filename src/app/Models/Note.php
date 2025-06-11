<?php

namespace App\Models;

interface NoteInterface
{
    public function getId(): int;
    public function getDate(): string;
    public function getContent(): string;
    public function getCreatedAt(): \DateTime;
    public function getUpdatedAt(): \DateTime;

}

class Note implements NoteInterface
{
    public function __construct(
        public int $id,
        public string $date,
        public string $content,
        public \DateTime $createdAt,
        public \DateTime $updatedAt
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
