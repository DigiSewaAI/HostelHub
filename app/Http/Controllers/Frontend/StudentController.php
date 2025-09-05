<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students for public view.
     */
    public function index()
    {
        // Add your logic to fetch and display students
        return view('frontend.students.index');
    }

    /**
     * Display the current user's students.
     */
    public function myStudents()
    {
        // Add your logic to fetch and display the current user's students
        return view('frontend.students.my-students');
    }
}
