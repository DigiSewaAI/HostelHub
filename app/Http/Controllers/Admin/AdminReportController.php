<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('reporter', 'reportable');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('admin.network.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load('reporter', 'reportable', 'reviewer');
        return view('admin.network.reports.show', compact('report'));
    }

    public function markReviewed(Report $report)
    {
        $report->update([
            'status' => 'reviewed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Optionally take action on the reported content (like hide, warn, etc.)
        // This can be done via separate actions

        return redirect()->route('admin.network.reports.index')
            ->with('success', 'Report marked as reviewed.');
    }

    public function dismiss(Report $report)
    {
        $report->update([
            'status' => 'dismissed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.network.reports.index')
            ->with('success', 'Report dismissed.');
    }
}
