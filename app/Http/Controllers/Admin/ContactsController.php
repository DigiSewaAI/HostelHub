<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function index()
    {
        $submissions = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com']
        ];

        return view('admin.contacts.index', compact('submissions'));
    }

    public function show($id)
    {
        $submission = [
            'id' => $id,
            'name' => 'Sample User',
            'email' => 'user@example.com',
            'message' => 'Test message content'
        ];

        return view('admin.contacts.show', compact('submission'));
    }

    public function destroy($id)
    {
        // Placeholder deletion logic
        return redirect()->route('admin.contacts.index')
            ->with('success', "Submission #$id deleted successfully");
    }
}
