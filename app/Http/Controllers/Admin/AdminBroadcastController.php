<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminBroadcastController extends Controller
{
    public function index(Request $request)
    {
        $query = BroadcastMessage::with('sender');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $broadcasts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.network.broadcasts.index', compact('broadcasts'));
    }

    public function show(BroadcastMessage $broadcast)
    {
        $broadcast->load('sender');
        return view('admin.network.broadcasts.show', compact('broadcast'));
    }

    public function approve(BroadcastMessage $broadcast)
    {
        $broadcast->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejected_reason' => null,
        ]);

        // Optionally trigger sending logic (e.g., queue job to send to all recipients)
        // For now, we just mark it approved; the actual sending might be handled by a separate process.

        return redirect()->route('admin.network.broadcasts.index')
            ->with('success', 'Broadcast approved.');
    }

    public function reject(Request $request, BroadcastMessage $broadcast)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:1000',
        ]);

        $broadcast->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => null,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'rejected_reason' => $request->rejected_reason,
        ]);

        // Notify owner about rejection

        return redirect()->route('admin.network.broadcasts.index')
            ->with('success', 'Broadcast rejected.');
    }
}
