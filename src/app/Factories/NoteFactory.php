<?php

namespace App\Factories;

use App\Models\Note;

class NoteFactory
{
    public static function create(string $date, string $content): Note
    {
        return new Note(0, $date, $content, new \DateTime, new \DateTime);
    }

    public static function fromArray(array $data): Note
    {
        return new Note(
            id: $data['id'] ?? 0,
            date: $data['date'] ?? '',
            content: $data['content'] ?? '',
            createdAt: isset($data['created_at']) ? new \DateTime($data['created_at']) : new \DateTime,
            updatedAt: isset($data['updated_at']) ? new \DateTime($data['updated_at']) : new \DateTime
        );
    }

    public static function fromJson(string $json): Note
    {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON data');
        }

        return self::fromArray($data);
    }
}
