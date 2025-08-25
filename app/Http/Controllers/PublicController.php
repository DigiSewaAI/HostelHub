<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Hostel;
use App\Models\Meal;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PublicController extends Controller
{
    /**
     * Show the public home page.
     */
    public function home(): View
    {
        // 1. Featured Rooms (Available rooms with at least one vacancy)
        $featuredRooms = Room::where('status', 'available')
            ->withCount('students')
            ->having('students_count', '<', DB::raw('capacity'))
            ->orderBy('price')
            ->limit(3)
            ->get();

        // 2. System Metrics
        $metrics = [
            'total_hostels'     => Hostel::count(),
            'total_students'    => Student::where('status', 'active')->count(),
            'total_rooms'       => Room::count(),
            'available_rooms'   => Room::where('status', 'available')->count(),
            'occupancy_rate'    => $this->getOccupancyRate(),
        ];

        // 3. Featured Gallery Items
        $galleryItems = collect();
        if (class_exists(Gallery::class)) {
            $galleryItems = Gallery::where('category', 'featured')
                ->latest()
                ->take(6)
                ->get();
        }

        // 4. Upcoming Meals (Today + next 2 days)
        $meals = collect();
        if (class_exists(Meal::class)) {
            $meals = Meal::whereDate('date', '>=', now())
                ->orderBy('date')
                ->limit(3)
                ->get();
        }

        return view('public.home', compact(
            'featuredRooms',
            'metrics',
            'galleryItems',
            'meals'
        ));
    }

    /**
     * Calculate room occupancy rate.
     */
    private function getOccupancyRate(): float
    {
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();

        return $totalRooms > 0
            ? round(($occupiedRooms / $totalRooms) * 100, 2)
            : 0.0;
    }

    // Basic page routes
    public function about(): View
    {
        return view('about');
    }
    public function features(): View
    {
        return view('features');
    }
    public function howItWorks(): View
    {
        return view('how-it-works');
    }
    public function pricing(): View
    {
        return view('pricing.index');
    }
    public function reviews(): View
    {
        return view('reviews');
    }
    public function contact(): View
    {
        return view('contact');
    }

    // Legal pages
    public function privacy(): View
    {
        return view('legal.privacy');
    }
    public function terms(): View
    {
        return view('legal.terms');
    }
    public function cookies(): View
    {
        return view('legal.cookies');
    }

    public function demo()
    {
        return view('pages.demo');
    }

    // SEO routes
    public function sitemap()
    {
        $content = view('seo.sitemap')->render();
        return Response::make($content, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots()
    {
        $content = view('seo.robots')->render();
        return Response::make($content, 200, ['Content-Type' => 'text/plain']);
    }
}
