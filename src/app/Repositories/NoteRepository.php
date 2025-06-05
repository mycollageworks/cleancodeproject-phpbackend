<?php

namespace App\Repositories;

use App\Factories\NoteFactory;
use App\Models\Note;

class NoteRepository
{
  public function all(): array
  {
    $db = \Core\DB::getInstance();
    $sql = "SELECT * FROM notes ORDER BY created_at DESC";
    $result = $db->fetchAll($sql);

    // bind to Note model
    $notes = [];
    foreach ($result as $row) {
      $note = NoteFactory::fromArray($row);
      $notes[] = $note;
    }

    return $notes;
  }

  public function create(Note $note)
  {
    // Bind the Note model to the database
    $db = \Core\DB::getInstance();
    $sql = "INSERT INTO notes ('date', content, created_at, updated_at) VALUES (:dateStr, :content, :created_at, :updated_at)";
    $params = [
      'dateStr' => $note->date,
      'content' => $note->content,
      'created_at' => $note->createdAt->format('Y-m-d H:i:s'),
      'updated_at' => $note->updatedAt->format('Y-m-d H:i:s'),
    ];
    $isSuccess = $db->insert($sql, $params);
    if (!$isSuccess) {
      throw new \Exception("Failed to create note");
    }

    // Return the created note with the new ID
    $note->id = $db->raw()->lastInsertId();
    return $note;
  }
}
