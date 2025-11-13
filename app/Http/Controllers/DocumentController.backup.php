<?php

namespace App\Http\Controllers;

use App\Models\StudentDocument;
use App\Models\Student;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDocumentRequest;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        // Admin: all organizations, Hostel Manager: current organization only
        $query = StudentDocument::with(['student.user', 'hostel', 'organization']);

        if (auth()->user()->hasRole('hostel_manager')) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('original_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('student.user', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by document type
        if ($request->has('document_type') && $request->document_type != '') {
            $query->where('document_type', $request->document_type);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $documents = $query->latest()->paginate(20);

        $documentTypes = StudentDocument::DOCUMENT_TYPES;

        if (auth()->user()->hasRole('admin')) {
            return view('admin.documents.index', compact('documents', 'documentTypes'));
        }

        return view('owner.documents.index', compact('documents', 'documentTypes'));
    }

    public function create()
    {
        // Only hostel managers can upload
        $students = Student::where('organization_id', auth()->user()->organization_id)
            ->with('user')
            ->get();
        $documentTypes = StudentDocument::DOCUMENT_TYPES;

        return view('owner.documents.create', compact('students', 'documentTypes'));
    }

    public function store(StoreDocumentRequest $request)
    {
        try {
            $file = $request->file('document');
            $organizationId = auth()->user()->organization_id;

            // File upload with organization-wise path
            $path = $file->store(
                'documents/organization_' . $organizationId,
                'public'
            );

            // Get student's hostel_id
            $student = Student::findOrFail($request->student_id);

            StudentDocument::create([
                'organization_id' => $organizationId,
                'student_id' => $request->student_id,
                'hostel_id' => $student->hostel_id,
                'uploaded_by' => auth()->id(),
                'document_type' => $request->document_type,
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'description' => $request->description,
                'expiry_date' => $request->expiry_date,
            ]);

            return redirect()->route('owner.documents.index')
                ->with('success', 'कागजात सफलतापूर्वक अपलोड गरियो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'कागजात अपलोड गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    public function show(StudentDocument $document)
    {
        // Authorization check - user can only view documents from their organization
        $this->authorize('view', $document);

        $document->load(['student.user', 'hostel', 'uploader']);

        if (auth()->user()->hasRole('admin')) {
            return view('admin.documents.show', compact('document'));
        }

        return view('owner.documents.show', compact('document'));
    }

    public function download(StudentDocument $document)
    {
        $this->authorize('download', $document);

        if (!Storage::disk('public')->exists($document->stored_path)) {
            return redirect()->back()->with('error', 'कागजात फाइल भेटिएन');
        }

        return Storage::disk('public')->download($document->stored_path, $document->original_name);
    }

    public function search(Request $request)
    {
        // Multi-tenant search implementation
        $query = StudentDocument::with(['student.user', 'hostel']);

        if (auth()->user()->hasRole('hostel_manager')) {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        $results = $query->when($request->student_name, function ($query) use ($request) {
            return $query->whereHas('student.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->student_name . '%');
            });
        })
            ->when($request->document_type, function ($query) use ($request) {
                return $query->where('document_type', $request->document_type);
            })
            ->when($request->start_date, function ($query) use ($request) {
                return $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                return $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->latest()
            ->get();

        $documentTypes = StudentDocument::DOCUMENT_TYPES;

        return view('owner.documents.search', compact('results', 'documentTypes'));
    }

    public function destroy(StudentDocument $document)
    {
        $this->authorize('delete', $document);

        try {
            // Delete physical file
            if (Storage::disk('public')->exists($document->stored_path)) {
                Storage::disk('public')->delete($document->stored_path);
            }

            $document->delete();

            return redirect()->route('owner.documents.index')
                ->with('success', 'कागजात सफलतापूर्वक मेटाइयो');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'कागजात मेटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }
}
