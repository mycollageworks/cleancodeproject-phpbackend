<?php

namespace App\Repositories;

use App\Factories\NoteFactory;
use App\Models\Note;

class NoteRepository
{
    public function all(): array
    {
        $db = \Core\DB::getInstance();
        $sql = 'SELECT * FROM notes WHERE deleted_at IS NULL ORDER BY created_at DESC';
        $result = $db->fetchAll($sql);

        // bind to Note model
        $notes = [];
        foreach ($result as $row) {
            $note = NoteFactory::fromArray($row);
            $notes[] = $note;
        }

        return $notes;
    }

    public function find(int $id): ?Note
    {
        $db = \Core\DB::getInstance();
        $sql = 'SELECT * FROM notes WHERE id = :id and deleted_at IS NULL';
        $params = ['id' => $id];
        $row = $db->fetch($sql, $params);

        if (! $row) {
            return null; // Note not found
        }

        return NoteFactory::fromArray($row);
    }

    public function create(Note $note)
    {
        // Bind the Note model to the database
        $db = \Core\DB::getInstance();
        $sql = 'INSERT INTO notes (date, content, created_at, updated_at) VALUES (:dateStr, :content, :created_at, :updated_at)';
        $params = [
            'dateStr' => $note->date,
            'content' => $note->content,
            'created_at' => $note->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $note->updatedAt->format('Y-m-d H:i:s'),
        ];
        $isSuccess = $db->insert($sql, $params);
        if (! $isSuccess) {
            throw new \Exception('Failed to create note');
        }

        // Return the created note with the new ID
        $note->id = $db->raw()->lastInsertId();

        return $note;
    }

    public function update(Note $note): bool
    {
        $db = \Core\DB::getInstance();
        $sql = 'UPDATE notes SET date = :dateStr, content = :content, updated_at = :updated_at WHERE id = :id and deleted_at IS NULL';
        $params = [
            'id' => $note->id,
            'dateStr' => $note->date,
            'content' => $note->content,
            'updated_at' => $note->updatedAt->format('Y-m-d H:i:s'),
        ];
        $pdoStatement = $db->query($sql, $params);

        if (! $pdoStatement) {
            throw new \Exception("Failed to update note with ID {$note->id}");
        }

        return $pdoStatement->rowCount() > 0; // Return true if at least one row was updated
    }

    public function delete(int $id): bool
    {
        $db = \Core\DB::getInstance();
        // soft delete, only change the deleted_at column to current time
        $sql = 'UPDATE notes SET deleted_at = NOW() WHERE id = :id';
        $params = ['id' => $id];
        $pdoStatement = $db->update($sql, $params);

        if (! $pdoStatement) {
            throw new \Exception("Failed to delete note with ID {$id}");
        }

        return true; // Return true if at least one row was deleted
    }
}
