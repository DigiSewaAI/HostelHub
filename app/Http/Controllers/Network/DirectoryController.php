<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Services\DirectoryService;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    protected $directoryService;

    public function __construct(DirectoryService $directoryService)
    {
        $this->directoryService = $directoryService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['city', 'services', 'pricing_category', 'min_size', 'max_size', 'verified_only']);
        $owners = $this->directoryService->searchOwners($filters);
        return view('network.directory.index', compact('owners', 'filters'));
    }
}
