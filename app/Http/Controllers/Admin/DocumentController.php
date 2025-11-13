<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents based on user role.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            \Log::info("=== DOCUMENT CONTROLLER START ===");
            \Log::info("User: " . $user->name . " (ID: " . $user->id . ")");
            \Log::info("Roles: " . $user->getRoleNames()->implode(', '));

            $query = Document::with(['student.user', 'hostel', 'uploader', 'organization']);

            // ✅ FIXED: Proper role-based data scoping with tenant context
            if ($user->hasRole('admin')) {
                \Log::info("Admin access - showing all documents");
                $documents = $query->latest();
            } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                \Log::info("Owner/hostel_manager access detected");

                // ✅ FIXED: Get organization from session for tenant context
                $organizationId = session('current_organization_id');
                \Log::info("Session organization ID: " . $organizationId);

                if ($organizationId) {
                    $organization = Organization::find($organizationId);
                    \Log::info("Organization found: " . ($organization->name ?? 'Unknown') . " (ID: " . $organizationId . ")");

                    // Get students in organization's hostels
                    $hostelIds = $organization->hostels->pluck('id');
                    $studentIds = Student::whereIn('hostel_id', $hostelIds)->pluck('id');

                    $documents = $query->whereIn('student_id', $studentIds)->latest();
                    \Log::info("Filtering documents for organization. Hostels: " . $hostelIds->count() . ", Students: " . $studentIds->count());
                } else {
                    \Log::warning("No organization found in session for user: " . $user->id);
                    // Emergency fallback: get user's first organization
                    $userOrganization = $user->organizations()->first();
                    if ($userOrganization) {
                        session(['current_organization_id' => $userOrganization->id]);
                        $hostelIds = $userOrganization->hostels->pluck('id');
                        $studentIds = Student::whereIn('hostel_id', $hostelIds)->pluck('id');
                        $documents = $query->whereIn('student_id', $studentIds)->latest();
                        \Log::info("Using emergency fallback organization: " . $userOrganization->name);
                    } else {
                        \Log::error("No organization available for user: " . $user->id);
                        $documents = $query->where('id', 0); // No documents
                    }
                }
            } else {
                // Students can only see their own documents
                $student = $user->student;
                if ($student) {
                    $documents = $query->where('student_id', $student->id)->latest();
                } else {
                    $documents = $query->where('id', 0); // No documents
                }
            }

            // Count before pagination
            $countBeforePagination = $documents->count();
            \Log::info("Documents count before pagination: " . $countBeforePagination);

            // ✅ SECURITY: Apply filters with input validation
            $documents = $this->applyFilters($documents, $request);

            $documents = $documents->paginate(10);

            \Log::info("Documents count after pagination: " . $documents->count());
            \Log::info("=== DOCUMENT CONTROLLER END ===");

            return $this->roleBasedView('documents.index', [
                'documents' => $documents,
                'documentTypes' => $this->getDocumentTypes(),
                'organizations' => $user->hasRole('admin') ? Organization::all() : collect()
            ]);
        } catch (\Exception $e) {
            \Log::error('Document index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'कागजातहरू लोड गर्न असफल भयो');
        }
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        try {
            $user = Auth::user();

            // ✅ SECURITY: Role-based data access with tenant context
            if ($user->hasRole('admin')) {
                $students = Student::with('user')->where('status', 'active')->get();
                $hostels = Hostel::all();
            } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                $organizationId = session('current_organization_id');
                
                if (!$organizationId) {
                    return redirect()->route($this->getRoleRoute('documents.index'))
                        ->with('error', 'तपाईंको संस्था फेला परेन। कृपया पुन: लगइन गर्नुहोस्।');
                }

                $organization = Organization::find($organizationId);
                if (!$organization) {
                    return redirect()->route($this->getRoleRoute('documents.index'))
                        ->with('error', 'तपाईंको संस्था फेला परेन');
                }

                $hostelIds = $organization->hostels->pluck('id');
                $students = Student::whereIn('hostel_id', $hostelIds)
                    ->with('user')
                    ->where('status', 'active')
                    ->get();
                $hostels = $organization->hostels;
            } else {
                abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
            }

            return $this->roleBasedView('documents.create', compact('students', 'hostels'));
        } catch (\Exception $e) {
            \Log::error('Document create form error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'फारम लोड गर्न असफल भयो');
        }
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {
        \Log::info("=== DOCUMENT STORE START ===");
        \Log::info("All request data:", $request->all());
        
        DB::beginTransaction();

        try {
            $user = Auth::user();
            
            // ✅ FIXED: Proper document type validation
            $allowedDocumentTypes = array_keys($this->getDocumentTypes());
            
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:students,id',
                'document_type' => ['required', 'string', Rule::in($allowedDocumentTypes)],
                'title' => 'required|string|max:255',
                'document_number' => 'nullable|string|max:255',
                'issue_date' => 'required|date',
                'expiry_date' => 'nullable|date|after:issue_date',
                'description' => 'nullable|string',
                'file_path' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
                'status' => 'required|in:active,inactive,expired'
            ], [
                'student_id.required' => 'विद्यार्थी चयन गर्नुपर्छ',
                'document_type.required' => 'कागजातको प्रकार चयन गर्नुपर्छ',
                'document_type.in' => 'अमान्य कागजात प्रकार चयन गरिएको छ',
                'title.required' => 'कागजातको शीर्षक आवश्यक छ',
                'issue_date.required' => 'जारी मिति आवश्यक छ',
                'file_path.required' => 'कृपया कागजात फाइल छनौट गर्नुहोस्',
                'file_path.mimes' => 'फाइल प्रकार PDF, JPG, JPEG, PNG, DOC, DOCX मात्र स्वीकार्य छ',
                'file_path.max' => 'फाइल साइज 10MB भन्दा ठूलो हुनु हुँदैन',
                'expiry_date.after' => 'समाप्ति मिति जारी मिति भन्दा पछि हुनुपर्छ'
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validatedData = $validator->validated();

            // ✅ SECURITY: Authorization check
            if (!$this->authorizeDocumentCreation($user, $validatedData['student_id'])) {
                abort(403, 'तपाईंसँग यो कागजात सिर्जना गर्ने अनुमति छैन');
            }

            // Handle file upload
            $fileData = $this->handleFileUpload($request->file('file_path'), $validatedData['student_id']);
            if (!$fileData) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'फाइल अपलोड गर्दा त्रुटि भयो');
            }

            // Get student and set organization context
            $student = Student::with('hostel')->findOrFail($validatedData['student_id']);
            
            // ✅ FIXED: Set tenant context properly
            $organizationId = session('current_organization_id') ?? $student->organization_id;

            // Create document with tenant context
            $documentData = array_merge($validatedData, $fileData, [
                'hostel_id' => $student->hostel_id,
                'organization_id' => $organizationId,
                'uploaded_by' => $user->id,
            ]);

            Document::create($documentData);

            DB::commit();

            \Log::info("Document created successfully for student: " . $student->id);
            \Log::info("=== DOCUMENT STORE END ===");

            return redirect()->route($this->getRoleRoute('documents.index'))
                ->with('success', 'कागजात सफलतापूर्वक थपियो!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Document store error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->with('error', 'कागजात थप्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        try {
            $user = Auth::user();

            // ✅ SECURITY: Authorization check
            if (!$this->authorizeDocumentAccess($user, $document)) {
                abort(403, 'तपाईंसँग यो कागजात हेर्ने अनुमति छैन');
            }

            $document->load(['student.user', 'hostel', 'uploader']);

            return $this->roleBasedView('documents.show', compact('document'));
        } catch (\Exception $e) {
            \Log::error('Document show error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'कागजात लोड गर्न असफल भयो');
        }
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document)
    {
        try {
            $user = Auth::user();

            // ✅ SECURITY: Authorization check
            if (!$this->authorizeDocumentAccess($user, $document)) {
                abort(403, 'तपाईंसँग यो कागजात सम्पादन गर्ने अनुमति छैन');
            }

            // ✅ SECURITY: Role-based data access
            if ($user->hasRole('admin')) {
                $students = Student::with('user')->where('status', 'active')->get();
                $hostels = Hostel::all();
            } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                $organizationId = session('current_organization_id');
                
                if (!$organizationId) {
                    return redirect()->route($this->getRoleRoute('documents.index'))
                        ->with('error', 'तपाईंको संस्था फेला परेन');
                }

                $organization = Organization::find($organizationId);
                $hostelIds = $organization->hostels->pluck('id');
                $students = Student::whereIn('hostel_id', $hostelIds)
                    ->with('user')
                    ->where('status', 'active')
                    ->get();
                $hostels = $organization->hostels;
            } else {
                $students = collect([$user->student]);
                $hostels = collect([$user->student->hostel]);
            }

            return $this->roleBasedView('documents.edit', compact('document', 'students', 'hostels'));
        } catch (\Exception $e) {
            \Log::error('Document edit form error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'सम्पादन फारम लोड गर्न असफल भयो');
        }
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Document $document)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            // ✅ SECURITY: Authorization check
            if (!$this->authorizeDocumentAccess($user, $document)) {
                abort(403, 'तपाईंसँग यो कागजात अपडेट गर्ने अनुमति छैन');
            }

            // ✅ FIXED: Proper document type validation with allowed values
            $allowedDocumentTypes = array_keys($this->getDocumentTypes());

            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:students,id',
                'document_type' => ['required', 'string', Rule::in($allowedDocumentTypes)],
                'title' => 'required|string|max:255',
                'document_number' => 'nullable|string|max:255',
                'issue_date' => 'required|date',
                'expiry_date' => 'nullable|date|after:issue_date',
                'description' => 'nullable|string|max:1000',
                'file_path' => 'sometimes|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
                'status' => 'required|in:active,inactive,expired'
            ], [
                'document_type.in' => 'अमान्य कागजात प्रकार चयन गरिएको छ',
                'file_path.mimes' => 'फाइल प्रकार PDF, JPG, JPEG, PNG, DOC, DOCX मात्र स्वीकार्य छ',
                'file_path.max' => 'फाइल साइज 10MB भन्दा ठूलो हुनु हुँदैन'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validatedData = $validator->validated();

            // Handle file upload if new file is provided
            if ($request->hasFile('file_path')) {
                // Delete old file
                if (Storage::disk('public')->exists($document->stored_path)) {
                    Storage::disk('public')->delete($document->stored_path);
                }

                $fileData = $this->handleFileUpload($request->file('file_path'), $validatedData['student_id']);
                if (!$fileData) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'फाइल अपलोड गर्दा त्रुटि भयो');
                }
                $validatedData = array_merge($validatedData, $fileData);
            } else {
                // Keep existing file data
                unset($validatedData['file_path']);
            }

            // Update student and hostel if changed
            if (isset($validatedData['student_id']) && $validatedData['student_id'] != $document->student_id) {
                $student = Student::with('hostel')->findOrFail($validatedData['student_id']);
                $validatedData['hostel_id'] = $student->hostel_id;
                $validatedData['organization_id'] = $student->organization_id;
            }

            $document->update($validatedData);

            DB::commit();

            return redirect()->route($this->getRoleRoute('documents.index'))
                ->with('success', 'कागजात सफलतापूर्वक अपडेट भयो!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Document update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'कागजात अपडेट गर्दा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Download the specified document.
     */
    public function download(Document $document)
    {
        try {
            $user = Auth::user();

            // ✅ SECURITY: Authorization check
            if (!$this->authorizeDocumentAccess($user, $document)) {
                abort(403, 'तपाईंसँग यो कागजात डाउनलोड गर्ने अनुमति छैन');
            }

            if (!Storage::disk('public')->exists($document->stored_path)) {
                abort(404, 'फाइल भेटिएन');
            }

            // ✅ SECURITY: Secure file download with proper headers
            return Storage::disk('public')->download(
                $document->stored_path,
                $this->sanitizeFilename($document->original_name)
            );
        } catch (\Exception $e) {
            \Log::error('Document download error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'फाइल डाउनलोड गर्न असफल भयो');
        }
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Document $document)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            // ✅ SECURITY: Authorization check
            if (!$this->authorizeDocumentDeletion($user, $document)) {
                abort(403, 'तपाईंसँग यो कागजात मेटाउने अनुमति छैन');
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($document->stored_path)) {
                Storage::disk('public')->delete($document->stored_path);
            }

            $document->delete();

            DB::commit();

            return redirect()->route($this->getRoleRoute('documents.index'))
                ->with('success', 'कागजात सफलतापूर्वक मेटाइयो!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Document destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'कागजात मेटाउँदा त्रुटि भयो: ' . $e->getMessage());
        }
    }

    /**
     * Search documents.
     */
    public function search(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Document::with(['student.user', 'hostel']);

            // ✅ SECURITY: Role-based data scoping
            if ($user->hasRole('admin')) {
                // Admin can search all documents
            } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                $organizationId = session('current_organization_id');
                if (!$organizationId) {
                    return $this->roleBasedView('documents.search', [
                        'results' => collect(),
                        'documentTypes' => $this->getDocumentTypes()
                    ])->with('error', 'तपाईंको संस्था फेला परेन');
                }
                
                $organization = Organization::find($organizationId);
                $hostelIds = $organization->hostels->pluck('id');
                $query->whereIn('hostel_id', $hostelIds);
            } else {
                // Students can only search their own documents
                $student = $user->student;
                if ($student) {
                    $query->where('student_id', $student->id);
                } else {
                    $query->where('id', 0);
                }
            }

            // ✅ SECURITY: Apply search filters with validation
            $results = $this->applySearchFilters($query, $request)->get();

            return $this->roleBasedView('documents.search', [
                'results' => $results,
                'documentTypes' => $this->getDocumentTypes()
            ]);
        } catch (\Exception $e) {
            \Log::error('Document search error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'खोज गर्दा त्रुटि भयो');
        }
    }

    // ✅ SECURITY: HELPER METHODS

    /**
     * Handle secure file upload with tenant context
     */
    private function handleFileUpload($file, $studentId = null)
    {
        try {
            \Log::info("Starting file upload for student: " . $studentId);

            $originalName = $file->getClientOriginalName();
            $safeName = $this->sanitizeFilename($originalName);
            $fileName = time() . '_' . $safeName;

            \Log::info("Original name: " . $originalName);
            \Log::info("Safe name: " . $safeName);
            \Log::info("File name: " . $fileName);

            // ✅ FIXED: Tenant-aware file storage
            $organizationId = session('current_organization_id');
            $directory = 'documents';
            
            if ($organizationId) {
                $directory .= '/organization_' . $organizationId;
            }
            
            if ($studentId) {
                $directory .= '/student_' . $studentId;
            }

            // Ensure documents directory exists
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory, 0755, true);
            }

            $filePath = $file->storeAs($directory, $fileName, 'public');

            \Log::info("File stored at: " . $filePath);
            \Log::info("File size: " . $file->getSize());
            \Log::info("MIME type: " . $file->getMimeType());

            return [
                'stored_path' => $filePath,
                'original_name' => $originalName,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ];
        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            \Log::error('File upload stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Sanitize filename to prevent directory traversal
     */
    private function sanitizeFilename($filename)
    {
        return preg_replace('/[^a-zA-Z0-9\-\._]/', '', $filename);
    }

    /**
     * Authorization for document creation
     */
    private function authorizeDocumentCreation($user, $studentId)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            $organizationId = session('current_organization_id');
            if (!$organizationId) return false;

            $student = Student::with('hostel')->find($studentId);
            return $student && $student->organization_id == $organizationId;
        }

        // Students can only create documents for themselves
        if ($user->hasRole('student')) {
            return $user->student && $user->student->id == $studentId;
        }

        return false;
    }

    /**
     * Authorization for document access
     */
    private function authorizeDocumentAccess($user, $document)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            $organizationId = session('current_organization_id');
            return $organizationId && $document->organization_id == $organizationId;
        }

        if ($user->hasRole('student')) {
            return $user->student && $document->student_id == $user->student->id;
        }

        return false;
    }

    /**
     * Authorization for document deletion
     */
    private function authorizeDocumentDeletion($user, $document)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Owners can delete documents from their organization
        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            $organizationId = session('current_organization_id');
            if (!$organizationId) return false;

            $hasOrganizationAccess = $document->organization_id == $organizationId;

            // Allow deletion if user uploaded the document or has organization access
            return $document->uploaded_by == $user->id || $hasOrganizationAccess;
        }

        // Students can only delete their own uploaded documents
        if ($user->hasRole('student')) {
            return $user->student &&
                $document->student_id == $user->student->id &&
                $document->uploaded_by == $user->id;
        }

        return false;
    }

    /**
     * Apply filters to documents query
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('original_name', 'like', "%{$search}%")
                    ->orWhereHas('student.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        return $query;
    }

    /**
     * Apply search filters
     */
    private function applySearchFilters($query, Request $request)
    {
        if ($request->filled('student_name')) {
            $query->whereHas('student.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->student_name . '%');
            });
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        return $query;
    }

    /**
     * Get document types
     */
    private function getDocumentTypes()
    {
        return [
            'admission_form' => 'भर्ना फारम',
            'id_card' => 'परिचय पत्र',
            'fee_receipt' => 'फी रसिद',
            'transfer_certificate' => 'सर्टिफिकेट',
            'character_certificate' => 'चरित्र प्रमाणपत्र',
            'academic_transcript' => 'अकादमिक ट्रान्सक्रिप्ट',
            'other' => 'अन्य'
        ];
    }

    /**
     * Return role-based view
     */
    private function roleBasedView($view, $data = [])
    {
        $user = Auth::user();
        $prefix = 'admin.';

        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            $prefix = 'owner.';
        } elseif ($user->hasRole('student')) {
            $prefix = 'student.';
        }

        return view($prefix . $view, $data);
    }

    /**
     * Get role-based route name
     */
    private function getRoleRoute($route)
    {
        $user = Auth::user();
        $prefix = 'admin.';

        if ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
            $prefix = 'owner.';
        } elseif ($user->hasRole('student')) {
            $prefix = 'student.';
        }

        return $prefix . $route;
    }
}