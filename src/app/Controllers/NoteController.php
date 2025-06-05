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
    $this->noteRepository = new NoteRepository();
  }

  public function index()
  {
    $notes = $this->noteRepository->all();
    return $this->jsonResponse([
      'notes' => $notes,
    ]);
  }

  public function store(Request $request)
  {
    $data = $request->getPostParam('note', []);
    $note = NoteFactory::create($data['date'], $data['content']);
    try {
      $this->noteRepository->create($note);
      return $this->successResponse('Note created successfully');
    } catch (\Throwable $th) {
      return $this->errorResponse('Failed to create note: ' . $th->getMessage(), 500);
    }
  }
}
