<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastMessage;
use App\Models\MarketplaceListing;
use App\Models\MessageThread;
use App\Models\OwnerNetworkProfile;
use App\Models\Hostel;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class AdminNetworkDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'active_hostels' => Hostel::where('status', 'active')->where('is_published', true)->count(),
            'pending_broadcasts' => BroadcastMessage::where('status', 'pending')->count(),
            'pending_listings' => MarketplaceListing::where('status', 'pending')->count(),
            'messages_today' => MessageThread::whereDate('created_at', today())->count(),
            'network_profiles' => OwnerNetworkProfile::count(),
            'pending_reports' => Report::pending()->count(),
            'suspended_hostels' => OwnerNetworkProfile::where('trust_level', 'suspended')->count(),
        ];

        $recentBroadcasts = BroadcastMessage::with('sender')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentListings = MarketplaceListing::with('owner')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::with('reporter', 'reportable')
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.network.dashboard', compact('stats', 'recentBroadcasts', 'recentListings', 'recentReports'));
    }
}
