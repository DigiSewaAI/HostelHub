<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hostel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OwnerPublicPageController extends Controller
{
    public function edit()
    {
        $hostel = auth()->user()->hostels()->first();

        if (!$hostel) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'तपाईंसँग कुनै होस्टल छैन। पहिले होस्टल सिर्जना गर्नुहोस्।');
        }

        return view('owner.public-page.edit', compact('hostel'));
    }

    public function updateAndPreview(Request $request)
    {
        $hostel = auth()->user()->hostels()->first();

        if (!$hostel) {
            return back()->with('error', 'होस्टल फेला परेन।');
        }

        $validated = $request->validate([
            'description' => 'nullable|string|max:2000',
            'theme_color' => 'nullable|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'theme' => 'required|in:modern,classic,vibrant,dark',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // ✅ NEW: Social Media Fields Validation
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'youtube_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($hostel->logo_path && Storage::disk('public')->exists($hostel->logo_path)) {
                Storage::disk('public')->delete($hostel->logo_path);
            }

            $path = $request->file('logo')->store('hostel_logos', 'public');
            $hostel->logo_path = $path;
        }

        // Update theme and other settings
        $hostel->theme = $validated['theme'];
        $hostel->theme_color = $validated['theme_color'] ?? $hostel->theme_color;
        $hostel->description = $validated['description'] ?? $hostel->description;

        // ✅ NEW: Update Social Media Fields
        $hostel->facebook_url = $validated['facebook_url'] ?? null;
        $hostel->instagram_url = $validated['instagram_url'] ?? null;
        $hostel->twitter_url = $validated['twitter_url'] ?? null;
        $hostel->tiktok_url = $validated['tiktok_url'] ?? null;
        $hostel->whatsapp_number = $validated['whatsapp_number'] ?? null;
        $hostel->youtube_url = $validated['youtube_url'] ?? null;
        $hostel->linkedin_url = $validated['linkedin_url'] ?? null;

        // Store in draft data for preview
        $hostel->draft_data = [
            'description' => $hostel->description,
            'theme_color' => $hostel->theme_color,
            'logo_path' => $hostel->logo_path,
            'theme' => $hostel->theme,
            // ✅ NEW: Store social media in draft data
            'facebook_url' => $hostel->facebook_url,
            'instagram_url' => $hostel->instagram_url,
            'twitter_url' => $hostel->twitter_url,
            'tiktok_url' => $hostel->tiktok_url,
            'whatsapp_number' => $hostel->whatsapp_number,
            'youtube_url' => $hostel->youtube_url,
            'linkedin_url' => $hostel->linkedin_url
        ];

        $hostel->save();

        return redirect()->route('hostels.preview', $hostel->slug)
            ->with('success', 'पूर्वावलोकन सफलतापूर्वक अपडेट गरियो।');
    }

    public function preview($slug)
    {
        $hostel = Hostel::where('slug', $slug)
            ->withCount([
                'reviews as approved_reviews_count' => function ($query) {
                    $query->where('status', 'approved');
                },
                'students'
            ])
            ->withAvg([
                'reviews as approved_reviews_avg_rating' => function ($query) {
                    $query->where('status', 'approved');
                }
            ], 'rating')
            ->firstOrFail();

        // Get paginated approved reviews
        $reviews = $hostel->reviews()
            ->where('status', 'approved')
            ->with('student.user')
            ->latest()
            ->paginate(6);

        // ✅ FIXED: Use same normalization as live page
        $logo = $this->normalizeLogoUrl($hostel->logo_path);
        $facilities = $this->parseFacilities($hostel->facilities);

        // Log for debugging
        \Log::info("Hostel Preview - Slug: {$slug}", [
            'logo_path' => $hostel->logo_path,
            'logo_normalized' => $logo,
            'facilities_raw' => $hostel->facilities,
            'facilities_parsed' => $facilities
        ]);

        return view('public.hostels.show', compact(
            'hostel',
            'reviews',
            'logo',
            'facilities'
        ))->with('preview', true);
    }

    public function publish(Request $request)
    {
        $hostel = auth()->user()->hostels()->first();

        if (!$hostel) {
            return back()->with('error', 'होस्टल फेला परेन।');
        }

        // Apply draft data if exists
        if ($hostel->draft_data) {
            $draft = $hostel->draft_data;
            if (isset($draft['description'])) $hostel->description = $draft['description'];
            if (isset($draft['theme_color'])) $hostel->theme_color = $draft['theme_color'];
            if (isset($draft['logo_path'])) $hostel->logo_path = $draft['logo_path'];
            if (isset($draft['theme'])) $hostel->theme = $draft['theme'];

            // ✅ NEW: Apply social media data from draft
            if (isset($draft['facebook_url'])) $hostel->facebook_url = $draft['facebook_url'];
            if (isset($draft['instagram_url'])) $hostel->instagram_url = $draft['instagram_url'];
            if (isset($draft['twitter_url'])) $hostel->twitter_url = $draft['twitter_url'];
            if (isset($draft['tiktok_url'])) $hostel->tiktok_url = $draft['tiktok_url'];
            if (isset($draft['whatsapp_number'])) $hostel->whatsapp_number = $draft['whatsapp_number'];
            if (isset($draft['youtube_url'])) $hostel->youtube_url = $draft['youtube_url'];
            if (isset($draft['linkedin_url'])) $hostel->linkedin_url = $draft['linkedin_url'];
        }

        if (empty($hostel->slug)) {
            $hostel->slug = Str::slug($hostel->name) . '-' . substr(uniqid(), -4);
        }

        $hostel->is_published = true;
        $hostel->published_at = now();
        $hostel->save();

        return back()->with('success', 'तपाईंको होस्टल पृष्ठ सफलतापूर्वक प्रकाशित गरियो!');
    }

    public function unpublish(Request $request)
    {
        $hostel = auth()->user()->hostels()->first();

        if (!$hostel) {
            return back()->with('error', 'होस्टल फेला परेन।');
        }

        $hostel->is_published = false;
        $hostel->save();

        return back()->with('success', 'तपाईंको होस्टल पृष्ठ अप्रकाशित गरियो।');
    }

    /**
     * ✅ FIXED: Logo URL normalization (same as PublicController)
     */
    private function normalizeLogoUrl($logoPath)
    {
        if (empty($logoPath)) {
            return null;
        }

        // If it's already a full URL, use it as is
        if (filter_var($logoPath, FILTER_VALIDATE_URL)) {
            return $logoPath;
        }

        // If it starts with http but might not validate as URL
        if (str_starts_with($logoPath, 'http')) {
            return $logoPath;
        }

        // Clean the path
        $cleanPath = ltrim($logoPath, '/');

        // Check if file exists in storage
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }

        // Fallback: try the original path
        return asset('storage/' . $cleanPath);
    }

    /**
     * ✅ FIXED: Facilities parsing (same as PublicController)
     */
    private function parseFacilities($facilitiesData)
    {
        if (empty($facilitiesData)) {
            return [];
        }

        // If it's already an array, return as is
        if (is_array($facilitiesData)) {
            return array_values(array_filter(array_map('trim', $facilitiesData)));
        }

        // If it's a string, try to parse it
        if (is_string($facilitiesData)) {
            // Try JSON decode first
            $decoded = json_decode($facilitiesData, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // JSON decoded successfully
                $flattened = [];
                array_walk_recursive($decoded, function ($item) use (&$flattened) {
                    if (is_string($item) && !empty(trim($item))) {
                        $trimmed = trim($item);
                        // Clean and decode Unicode
                        $cleaned = $this->cleanAndDecodeString($trimmed);
                        if (!empty($cleaned)) {
                            $flattened[] = $cleaned;
                        }
                    }
                });
                return array_values(array_unique(array_filter($flattened)));
            } else {
                // If not JSON, try different separators
                $facilities = preg_split('/\r\n|\r|\n|,/', $facilitiesData);
                $cleaned = array_map(function ($item) {
                    return $this->cleanAndDecodeString($item);
                }, $facilities);

                return array_values(array_unique(array_filter($cleaned, function ($item) {
                    return !empty($item) && $item !== '""' && $item !== "''";
                })));
            }
        }

        return [];
    }

    /**
     * ✅ NEW: Clean and decode string with Unicode support
     */
    private function cleanAndDecodeString($string)
    {
        $trimmed = trim($string);
        $trimmed = trim($trimmed, ' ,"\'[]{}');

        if (empty($trimmed) || $trimmed === '""' || $trimmed === "''") {
            return null;
        }

        // Handle Unicode escape sequences
        if (preg_match('/\\\\u[0-9a-fA-F]{4}/', $trimmed)) {
            $decoded = json_decode('"' . str_replace('"', '\\"', $trimmed) . '"');
            if ($decoded !== null && json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return $trimmed;
    }
}
