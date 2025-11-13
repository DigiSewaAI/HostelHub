<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * सेटिङहरूको सूची देखाउनुहोस्
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        try {
            // Cache settings for 1 hour to improve performance
            $settings = Cache::remember('settings_all', 3600, function () {
                return Setting::all()->groupBy('group');
            });

            $groups = Setting::distinct()->pluck('group');

            return view('admin.settings.index', compact('settings', 'groups'));
        } catch (\Exception $e) {
            Log::error('सेटिङहरू लोड गर्दा त्रुटि: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'सेटिङहरू लोड गर्न असफल भयो');
        }
    }

    /**
     * नयाँ सेटिङ सिर्जना गर्ने फारम देखाउनुहोस्
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        try {
            $groups = Setting::distinct()->pluck('group');
            return view('admin.settings.create', compact('groups'));
        } catch (\Exception $e) {
            Log::error('सेटिङ सिर्जना फारम लोड गर्दा त्रुटि: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'सेटिङ सिर्जना फारम लोड गर्न असफल भयो');
        }
    }

    /**
     * नयाँ सेटिङ भण्डारण गर्नुहोस्
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'key' => 'required|unique:settings,key|max:255|regex:/^[a-zA-Z0-9_\.]+$/',
                'value' => 'required|max:1000',
                'group' => 'required|max:255',
                'type' => 'required|in:text,number,textarea,select,boolean,email,url',
                'options' => 'nullable|required_if:type,select|json',
                'description' => 'nullable|max:500'
            ], [
                'key.required' => 'सेटिङ कि आवश्यक छ',
                'key.unique' => 'यो सेटिङ कि पहिले नै अवस्थित छ',
                'key.regex' => 'सेटिङ किमा केवल अक्षर, संख्या, अण्डरस्कोर र डट मात्र प्रयोग गर्न सकिन्छ',
                'value.required' => 'सेटिङ मान आवश्यक छ',
                'group.required' => 'सेटिङ समूह आवश्यक छ',
                'type.required' => 'सेटिङ प्रकार आवश्यक छ',
                'type.in' => 'अमान्य सेटिङ प्रकार',
                'options.required_if' => 'विकल्पहरू आवश्यक छन् जब प्रकार "select" हो',
                'options.json' => 'विकल्पहरू JSON ढाँचामा हुनुपर्छ'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ✅ SECURITY FIX: Use only validated data instead of request->all()
            $validatedData = $validator->validated();

            Setting::create($validatedData);

            // Clear settings cache
            Cache::forget('settings_all');

            return redirect()->route('admin.settings.index')
                ->with('success', 'सेटिङ सफलतापूर्वक सिर्जना गरियो');
        } catch (\Exception $e) {
            Log::error('सेटिङ सिर्जना गर्दा त्रुटि: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'सेटिङ सिर्जना गर्न असफल भयो')
                ->withInput();
        }
    }

    /**
     * विशेष सेटिङ देखाउनुहोस्
     *
     * @param string $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show(string $id)
    {
        try {
            $setting = Setting::findOrFail($id);
            return view('admin.settings.show', compact('setting'));
        } catch (\Exception $e) {
            Log::error('सेटिङ देखाउँदा त्रुटि: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'सेटिङ फेला पार्न असफल भयो');
        }
    }

    /**
     * सेटिङ सम्पादन गर्ने फारम देखाउनुहोस्
     *
     * @param string $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $id)
    {
        try {
            $setting = Setting::findOrFail($id);
            $groups = Setting::distinct()->pluck('group');

            return view('admin.settings.edit', compact('setting', 'groups'));
        } catch (\Exception $e) {
            Log::error('सेटिङ सम्पादन फारम लोड गर्दा त्रुटि: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'सेटिङ सम्पादन फारम लोड गर्न असफल भयो');
        }
    }

    /**
     * सेटिङ अपडेट गर्नुहोस्
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        try {
            $setting = Setting::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'key' => 'required|max:255|regex:/^[a-zA-Z0-9_\.]+$/|unique:settings,key,' . $id,
                'value' => 'required|max:1000',
                'group' => 'required|max:255',
                'type' => 'required|in:text,number,textarea,select,boolean,email,url',
                'options' => 'nullable|required_if:type,select|json',
                'description' => 'nullable|max:500'
            ], [
                'key.required' => 'सेटिङ कि आवश्यक छ',
                'key.unique' => 'यो सेटिङ कि पहिले नै अवस्थित छ',
                'key.regex' => 'सेटिङ किमा केवल अक्षर, संख्या, अण्डरस्कोर र डट मात्र प्रयोग गर्न सकिन्छ',
                'value.required' => 'सेटिङ मान आवश्यक छ',
                'group.required' => 'सेटिङ समूह आवश्यक छ',
                'type.required' => 'सेटिङ प्रकार आवश्यक छ',
                'type.in' => 'अमान्य सेटिङ प्रकार',
                'options.required_if' => 'विकल्पहरू आवश्यक छन् जब प्रकार "select" हो',
                'options.json' => 'विकल्पहरू JSON ढाँचामा हुनुपर्छ'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // ✅ SECURITY FIX: Use only validated data instead of request->all()
            $validatedData = $validator->validated();

            $setting->update($validatedData);

            // Clear settings cache
            Cache::forget('settings_all');

            return redirect()->route('admin.settings.index')
                ->with('success', 'सेटिङ सफलतापूर्वक अपडेट गरियो');
        } catch (\Exception $e) {
            Log::error('सेटिङ अपडेट गर्दा त्रुटि: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'सेटिङ अपडेट गर्न असफल भयो')
                ->withInput();
        }
    }

    /**
     * सेटिङ हटाउनुहोस्
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        try {
            $setting = Setting::findOrFail($id);

            // सिस्टम सेटिङहरू हटाउन नदिनुहोस्
            if ($setting->is_system) {
                return redirect()->back()
                    ->with('error', 'सिस्टम सेटिङहरू हटाउन अनुमति छैन');
            }

            $setting->delete();

            // Clear settings cache
            Cache::forget('settings_all');

            return redirect()->route('admin.settings.index')
                ->with('success', 'सेटिङ सफलतापूर्वक हटाइयो');
        } catch (\Exception $e) {
            Log::error('सेटिङ हटाउँदा त्रुटि: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'सेटिङ हटाउन असफल भयो');
        }
    }

    /**
     * बल्क सेटिङ अपडेट गर्नुहोस्
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'settings.*' => 'sometimes|max:1000'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $settings = $request->except('_token');

            // ✅ SECURITY FIX: Define allowed settings for bulk update to prevent mass assignment
            $allowedSettings = [
                'site_name',
                'site_title',
                'site_description',
                'site_keywords',
                'contact_email',
                'contact_phone',
                'contact_address',
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'payment_gateway',
                'currency',
                'timezone',
                'date_format',
                'items_per_page',
                'maintenance_mode',
                'registration_enabled',
                'facebook_url',
                'twitter_url',
                'instagram_url',
                'youtube_url',
                'meta_title',
                'meta_description',
                'logo_path',
                'favicon_path',
                'default_language',
                'email_verification',
                'auto_approve_bookings'
            ];

            foreach ($settings as $key => $value) {
                // ✅ SECURITY FIX: Only update allowed settings that exist
                if (in_array($key, $allowedSettings)) {
                    $setting = Setting::where('key', $key)->first();
                    if ($setting && !$setting->is_system) {
                        $setting->update(['value' => $value]);
                    }
                } else {
                    Log::warning('Attempted bulk update of disallowed setting: ' . $key, [
                        'user_id' => auth()->id(),
                        'ip' => $request->ip()
                    ]);
                }
            }

            // Clear settings cache
            Cache::forget('settings_all');

            return redirect()->back()
                ->with('success', 'सेटिङहरू सफलतापूर्वक अपडेट गरियो');
        } catch (\Exception $e) {
            Log::error('बल्क सेटिङ अपडेट गर्दा त्रुटि: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'सेटिङहरू अपडेट गर्न असफल भयो');
        }
    }

    /**
     * सेटिङहरूको API (अन्य कन्ट्रोलरहरूको लागि)
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        try {
            // Use caching for frequently accessed settings
            return Cache::remember('setting_' . $key, 3600, function () use ($key, $default) {
                $setting = Setting::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            });
        } catch (\Exception $e) {
            Log::error('सेटिङ API त्रुटि: ' . $e->getMessage());
            return $default;
        }
    }
}
