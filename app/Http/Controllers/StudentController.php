<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $students = [
            ['id' => 1, 'name' => 'Student One'],
            ['id' => 2, 'name' => 'Student Two']
        ];

        return view('students.index', compact('students'));
    }

    public function myStudents()
    {
        $students = [
            ['id' => Auth::id(), 'name' => 'My Student']
        ];

        return view('students.my', compact('students'));
    }
}
