<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of payment methods for owner's hostels.
     */
    public function index()
    {
        $user = Auth::user();

        // Get all hostels owned/managed by user
        $hostelIds = Hostel::where('owner_id', $user->id)
            ->orWhere('manager_id', $user->id)
            ->pluck('id')
            ->toArray();

        // FIX: Auto-fix any payment methods with order = 0 before displaying
        $this->fixZeroOrderMethods($hostelIds);

        $paymentMethods = PaymentMethod::whereIn('hostel_id', $hostelIds)
            ->with('hostel')
            ->orderBy('hostel_id')
            ->orderBy('order')
            ->orderBy('created_at')
            ->paginate(20);

        // Get statistics
        $stats = [
            'total' => PaymentMethod::whereIn('hostel_id', $hostelIds)->count(),
            'active' => PaymentMethod::whereIn('hostel_id', $hostelIds)->where('is_active', true)->count(),
            'default' => PaymentMethod::whereIn('hostel_id', $hostelIds)->where('is_default', true)->count(),
            'bank' => PaymentMethod::whereIn('hostel_id', $hostelIds)->where('type', 'bank')->count(),
            'digital' => PaymentMethod::whereIn('hostel_id', $hostelIds)->whereIn('type', ['esewa', 'khalti', 'fonepay'])->count(),
        ];

        return view('owner.payment_methods.index', compact('paymentMethods', 'stats'));
    }

    /**
     * Show payment methods for a specific hostel.
     */
    public function hostelPaymentMethods($hostelId)
    {
        $user = Auth::user();

        // Verify hostel belongs to user with proper error handling
        try {
            $hostel = Hostel::with(['paymentMethods' => function ($query) {
                $query->orderBy('order', 'asc')
                    ->orderBy('created_at', 'desc');
            }])
                ->where('id', $hostelId)
                ->where(function ($query) use ($user) {
                    $query->where('owner_id', $user->id)
                        ->orWhere('manager_id', $user->id);
                })
                ->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(403, 'तपाईंलाई यो होस्टेल हेर्ने अनुमति छैन।');
        }

        // FIX: Auto-fix any payment methods with order = 0 for this hostel
        $this->fixZeroOrderMethods([$hostelId]);

        // Reload payment methods after fixing order
        $paymentMethods = $hostel->paymentMethods()
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('owner.payment_methods.hostel', compact('hostel', 'paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create($hostelId = null)
    {
        $user = Auth::user();

        $hostels = Hostel::where('owner_id', $user->id)
            ->orWhere('manager_id', $user->id)
            ->get();

        if ($hostels->isEmpty()) {
            return redirect()->route('owner.hostels.index')
                ->with('warning', 'कृपया पहिले होस्टेल सिर्जना गर्नुहोस्।');
        }

        $selectedHostel = null;
        if ($hostelId) {
            $selectedHostel = $hostels->where('id', $hostelId)->first();
            if (!$selectedHostel) {
                abort(403, 'तपाईंलाई यो होस्टेल हेर्ने अनुमति छैन।');
            }
        }

        return view('owner.payment_methods.create', compact('hostels', 'selectedHostel'));
    }

    /**
     * Store a newly created payment method.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate hostel ownership
        $hostel = Hostel::where('id', $request->hostel_id)
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere('manager_id', $user->id);
            })
            ->firstOrFail();

        // Get validation rules with additional constraints
        $validated = $this->validateWithCustomRules($request, $hostel->id);

        try {
            // Handle QR code upload
            if ($request->hasFile('qr_code')) {
                $path = $request->file('qr_code')->store('payment_qr_codes', 'public');
                $validated['qr_code_path'] = $path;
            }

            // FIX: Always set proper order when creating new payment method
            if (empty($validated['order'])) {
                $maxOrder = PaymentMethod::where('hostel_id', $hostel->id)->max('order');
                // Start from 1 if no methods exist, otherwise increment from max
                $validated['order'] = ($maxOrder ?: 0) + 1;
            } elseif ($validated['order'] <= 0) {
                // Ensure order is never 0 or negative
                $maxOrder = PaymentMethod::where('hostel_id', $hostel->id)->max('order');
                $validated['order'] = ($maxOrder ?: 0) + 1;
            }

            // If setting as default, ensure it's active
            if (isset($validated['is_default']) && $validated['is_default'] && !isset($validated['is_active'])) {
                $validated['is_active'] = true;
            }

            // ENFORCEMENT: If setting as default, unset other defaults for this hostel
            if (isset($validated['is_default']) && $validated['is_default']) {
                // First, unset all other default methods for this hostel
                PaymentMethod::where('hostel_id', $hostel->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);

                // Ensure the new method is active
                $validated['is_active'] = true;
            }

            // Create payment method
            $paymentMethod = PaymentMethod::create($validated);

            // Reorder if there's a conflict
            PaymentMethod::reorderForHostel($hostel->id);

            return redirect()->route('owner.payment-methods.hostel', $hostel->id)
                ->with('success', 'भुक्तानी विधि सफलतापूर्वक थपियो!');
        } catch (\Exception $e) {
            Log::error('Payment method creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'भुक्तानी विधि थप्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        $user = Auth::user();

        // Verify ownership
        $hostel = Hostel::where('id', $paymentMethod->hostel_id)
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere('manager_id', $user->id);
            })
            ->firstOrFail();

        return view('owner.payment_methods.edit', compact('paymentMethod', 'hostel'));
    }

    /**
     * Update the specified payment method.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $user = Auth::user();

        // Verify ownership
        $hostel = Hostel::where('id', $paymentMethod->hostel_id)
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere('manager_id', $user->id);
            })
            ->firstOrFail();

        // Get validation rules with additional constraints
        $validated = $this->validateWithCustomRules($request, $hostel->id, $paymentMethod->id);

        try {
            // Handle QR code removal
            if ($request->has('remove_qr_code') && $request->remove_qr_code == '1') {
                if ($paymentMethod->qr_code_path && Storage::disk('public')->exists($paymentMethod->qr_code_path)) {
                    Storage::disk('public')->delete($paymentMethod->qr_code_path);
                }
                $validated['qr_code_path'] = null;
            }

            // Handle QR code upload
            if ($request->hasFile('qr_code')) {
                // Delete old QR code if exists
                if ($paymentMethod->qr_code_path && Storage::disk('public')->exists($paymentMethod->qr_code_path)) {
                    Storage::disk('public')->delete($paymentMethod->qr_code_path);
                }

                $path = $request->file('qr_code')->store('payment_qr_codes', 'public');
                $validated['qr_code_path'] = $path;
            }

            // FIX: Ensure order is never 0
            if (isset($validated['order']) && $validated['order'] <= 0) {
                $maxOrder = PaymentMethod::where('hostel_id', $hostel->id)
                    ->where('id', '!=', $paymentMethod->id)
                    ->max('order');
                $validated['order'] = ($maxOrder ?: 0) + 1;
            }

            // ENFORCEMENT: Handle default method logic
            if (isset($validated['is_default']) && $validated['is_default']) {
                // Unset all other default methods for this hostel
                PaymentMethod::where('hostel_id', $hostel->id)
                    ->where('id', '!=', $paymentMethod->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);

                // Ensure the method is active
                $validated['is_active'] = true;
            } elseif (isset($validated['is_default']) && !$validated['is_default'] && $paymentMethod->is_default) {
                // If unsetting default, find another active method to set as default
                $newDefault = PaymentMethod::where('hostel_id', $hostel->id)
                    ->where('id', '!=', $paymentMethod->id)
                    ->where('is_active', true)
                    ->first();

                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }

            // Can't deactivate if it's the only active method
            if (isset($validated['is_active']) && !$validated['is_active'] && $paymentMethod->is_default) {
                $otherActiveMethods = PaymentMethod::where('hostel_id', $hostel->id)
                    ->where('id', '!=', $paymentMethod->id)
                    ->where('is_active', true)
                    ->count();

                if ($otherActiveMethods === 0) {
                    return back()->withInput()->with('error', 'यो एक मात्र सक्रिय भुक्तानी विधि हो। अर्को विधि सक्रिय गर्नुहोस् पहिले।');
                }
            }

            // Update payment method
            $paymentMethod->update($validated);

            // Reorder if order was changed
            if (isset($validated['order']) && $validated['order'] != $paymentMethod->order) {
                PaymentMethod::reorderForHostel($hostel->id);
            }

            return redirect()->route('owner.payment-methods.hostel', $hostel->id)
                ->with('success', 'भुक्तानी विधि सफलतापूर्वक अद्यावधिक गरियो!');
        } catch (\Exception $e) {
            Log::error('Payment method update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'भुक्तानी विधि अद्यावधिक गर्न असफल: ' . $e->getMessage());
        }
    }

    /**
     * Toggle payment method active status.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $user = Auth::user();

        // Verify ownership
        $hostel = Hostel::where('id', $paymentMethod->hostel_id)
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere('manager_id', $user->id);
            })
            ->firstOrFail();

        try {
            if ($paymentMethod->is_active) {
                $paymentMethod->deactivate();
                $message = 'भुक्तानी विधि निष्क्रिय गरियो!';
            } else {
                $paymentMethod->activate();
                $message = 'भुक्तानी विधि सक्रिय गरियो!';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Set as default payment method.
     */
    public function setDefault(PaymentMethod $paymentMethod)
    {
        $user = Auth::user();

        // Verify ownership
        $hostel = Hostel::where('id', $paymentMethod->hostel_id)
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere('manager_id', $user->id);
            })
            ->firstOrFail();

        // Check if method can be set as default
        if (!$paymentMethod->is_active) {
            return back()->with('error', 'निष्क्रिय भुक्तानी विधिलाई मुख्य बनाउन सकिँदैन।');
        }

        // ENFORCEMENT: Unset all other default methods for this hostel first
        PaymentMethod::where('hostel_id', $hostel->id)
            ->where('id', '!=', $paymentMethod->id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        $paymentMethod->markAsDefault();

        return back()->with('success', 'यो भुक्तानी विधि मुख्यको रूपमा सेट गरियो!');
    }

    /**
     * Remove the specified payment method.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $user = Auth::user();

        // Verify ownership
        $hostel = Hostel::where('id', $paymentMethod->hostel_id)
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere('manager_id', $user->id);
            })
            ->firstOrFail();

        // Check if can be deleted
        if (!$paymentMethod->can_be_deleted) {
            return back()->with('error', 'यस भुक्तानी विधिलाई हटाउन सकिँदैन किनभने यसका भुक्तानी रेकर्डहरू छन् वा यो एक मात्र सक्रिय विधि हो।');
        }

        // Prevent deletion of default method without active replacement
        if ($paymentMethod->is_default) {
            $activeMethods = PaymentMethod::where('hostel_id', $hostel->id)
                ->where('id', '!=', $paymentMethod->id)
                ->where('is_active', true)
                ->count();

            if ($activeMethods === 0) {
                return back()->with('error', 'मुख्य भुक्तानी विधि हटाउनु अघि कम्तिमा एउटा अर्को सक्रिय विधि थप्नुहोस्।');
            }
        }

        try {
            // Delete QR code if exists
            if ($paymentMethod->qr_code_path && Storage::disk('public')->exists($paymentMethod->qr_code_path)) {
                Storage::disk('public')->delete($paymentMethod->qr_code_path);
            }

            $paymentMethod->delete();

            // FIX: Reorder remaining methods after deletion
            PaymentMethod::reorderForHostel($hostel->id);

            return redirect()->route('owner.payment-methods.hostel', $hostel->id)
                ->with('success', 'भुक्तानी विधि सफलतापूर्वक हटाइयो!');
        } catch (\Exception $e) {
            Log::error('Payment method deletion failed: ' . $e->getMessage());
            return back()->with('error', 'भुक्तानी विधि हटाउन असफल: ' . $e->getMessage());
        }
    }

    /**
     * Update payment method order.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:payment_methods,id',
            'order.*.position' => 'required|integer|min:1' // FIX: Changed from min:0 to min:1
        ]);

        $user = Auth::user();
        $hostelIds = Hostel::where('owner_id', $user->id)
            ->orWhere('manager_id', $user->id)
            ->pluck('id')
            ->toArray();

        try {
            // Group by hostel for bulk reordering
            $hostelGroups = [];

            foreach ($request->order as $item) {
                $paymentMethod = PaymentMethod::find($item['id']);

                // Verify ownership
                if ($paymentMethod && in_array($paymentMethod->hostel_id, $hostelIds)) {
                    // FIX: Ensure position is never 0
                    $position = max(1, $item['position']);
                    $hostelGroups[$paymentMethod->hostel_id][] = [
                        'id' => $item['id'],
                        'position' => $position
                    ];
                }
            }

            // Update order for each hostel
            foreach ($hostelGroups as $hostelId => $methods) {
                // Sort by position
                usort($methods, function ($a, $b) {
                    return $a['position'] <=> $b['position'];
                });

                // Update with sequential order starting from 1
                $order = 1;
                foreach ($methods as $method) {
                    PaymentMethod::where('id', $method['id'])->update(['order' => $order]);
                    $order++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'क्रम सफलतापूर्वक अद्यावधिक गरियो!'
            ]);
        } catch (\Exception $e) {
            Log::error('Order update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'क्रम अद्यावधिक गर्न असफल: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate with custom rules
     */
    private function validateWithCustomRules(Request $request, $hostelId, $paymentMethodId = null)
    {
        $rules = PaymentMethod::validationRules($paymentMethodId);

        // Add unique order constraint per hostel
        $rules['order'] = [
            'nullable',
            'integer',
            'min:1', // FIX: Ensure order is never 0
            Rule::unique('payment_methods')->where(function ($query) use ($hostelId, $paymentMethodId) {
                $query->where('hostel_id', $hostelId);
                if ($paymentMethodId) {
                    $query->where('id', '!=', $paymentMethodId);
                }
            })
        ];

        // Add validation for bank type
        if ($request->type == 'bank') {
            $rules['account_name'] = 'required|string|max:255';
            $rules['bank_name'] = 'required|string|max:255';
        }

        // Add validation for digital wallets
        if (in_array($request->type, ['esewa', 'khalti', 'fonepay', 'imepay'])) {
            $rules['mobile_number'] = 'required|string|max:20';
        }

        // Custom validation messages
        $messages = [
            'order.unique' => 'यो क्रम नम्बर यस होस्टेलमा पहिले नै प्रयोग गरिएको छ।',
            'order.min' => 'क्रम नम्बर १ वा सो भन्दा बढी हुनुपर्छ।',
            'account_name.required' => 'खाता धनीको नाम आवश्यक छ।',
            'bank_name.required' => 'बैंकको नाम आवश्यक छ।',
            'mobile_number.required' => 'मोबाइल नम्बर आवश्यक छ।',
        ];

        return $request->validate($rules, $messages);
    }

    /**
     * FIX: Auto-fix payment methods with order = 0
     */
    private function fixZeroOrderMethods(array $hostelIds): void
    {
        foreach ($hostelIds as $hostelId) {
            // Check if any payment method has order = 0 for this hostel
            $hasZeroOrder = PaymentMethod::where('hostel_id', $hostelId)
                ->where('order', 0)
                ->exists();

            if ($hasZeroOrder) {
                // Get all payment methods for this hostel ordered by created_at
                $methods = PaymentMethod::where('hostel_id', $hostelId)
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Reassign order starting from 1
                $order = 1;
                foreach ($methods as $method) {
                    $method->update(['order' => $order]);
                    $order++;
                }

                Log::info("Fixed order for hostel {$hostelId}: Reassigned orders for {$methods->count()} payment methods");
            }
        }
    }
}
