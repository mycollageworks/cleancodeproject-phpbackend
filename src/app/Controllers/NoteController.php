<?php

namespace App\Controllers;

use App\Factories\NoteFactory;
use App\Repositories\NoteRepository;
use Core\Controller;
use Core\Request;

class NoteController extends Controller
{
    private NoteRepository $noteRepository;

    public function __construct()
    {
        $this->noteRepository = new NoteRepository;
    }

    public function index(Request $request)
    {
        $searchTerm = $request->getQueryParam('search', '');
        $notes = $this->noteRepository->findBySearchTerm($searchTerm);

        return $this->jsonResponse([
            'notes' => $notes,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->getPostParam('note', []);

        // Validate required fields
        if (empty($data['date']) || empty($data['content'])) {
            return $this->errorResponse('Date and content are required', 400);
        }

        $note = NoteFactory::create($data['date'], $data['content']);
        try {
            $this->noteRepository->create($note);

            return $this->successResponse('Note created successfully');
        } catch (\Throwable $th) {
            return $this->errorResponse('Failed to create note: '.$th->getMessage(), 500);
        }
    }

    public function show(int $id)
    {
        $notes = $this->noteRepository->find($id);
        if (! $notes) {
            return $this->errorResponse('Note not found', 404);
        }

        return $this->jsonResponse([
            'note' => $notes,
        ]);
    }

    public function update(int $id, Request $request)
    {
        $data = $request->getPostParam('note', []);

        // Validate required fields
        if (empty($data['date']) || empty($data['content'])) {
            return $this->errorResponse('Date and content are required', 400);
        }

        $note = $this->noteRepository->find($id);
        if (! $note) {
            return $this->errorResponse('Note not found', 404);
        }

        // Update the note properties
        $note->date = $data['date'];
        $note->content = $data['content'];
        $note->updatedAt = new \DateTime;

        try {
            $this->noteRepository->update($note);

            return $this->successResponse('Note updated successfully');
        } catch (\Throwable $th) {
            return $this->errorResponse('Failed to update note: '.$th->getMessage(), 500);
        }
    }

    public function destroy(int $id)
    {
        $note = $this->noteRepository->find($id);
        if (! $note) {
            return $this->errorResponse('Note not found', 404);
        }

        try {
            $this->noteRepository->delete($id);

            return $this->successResponse('Note deleted successfully');
        } catch (\Throwable $th) {
            return $this->errorResponse('Failed to delete note: '.$th->getMessage(), 500);
        }
    }
}
