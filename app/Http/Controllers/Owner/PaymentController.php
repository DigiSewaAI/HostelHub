<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Hostel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Check if user has permission to access a specific payment
     */
    private function checkPaymentPermission(Payment $payment)
    {
        $user = Auth::user();

        $hostelIds = Hostel::where('owner_id', $user->id)
            ->orWhere('manager_id', $user->id)
            ->pluck('id')
            ->toArray();

        if (!in_array($payment->hostel_id, $hostelIds)) {
            abort(403, 'तपाईंसँग यो भुक्तानी हेर्ने अनुमति छैन');
        }
    }

    /**
     * SMART Logo Finder - AUTOMATIC र FULLY AUTONOMOUS
     */
    private function getHostelLogo($hostelId)
    {
        try {
            // 1. Hostel पत्ता लगाउने
            $hostel = Hostel::find($hostelId);
            if (!$hostel) {
                return $this->generateDefaultLogo('Hostel');
            }

            // 2. Database मा भएको logo check गर्ने
            if ($hostel->logo_path) {
                $path = storage_path('app/public/' . $hostel->logo_path);
                if (file_exists($path)) {
                    return $this->imageToBase64($path);
                }
            }

            // 3. Storage मा सबै logo files हेर्ने
            $logoDir = 'hostel_logos/';
            $allLogos = [];

            if (Storage::disk('public')->exists($logoDir)) {
                $files = Storage::disk('public')->files($logoDir);
                foreach ($files as $file) {
                    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                        $allLogos[] = basename($file);
                    }
                }
            }

            // 4. यदि logo files छन् भने
            if (!empty($allLogos)) {
                // Hostel ID अनुसार logo select गर्ने (round-robin)
                $logoIndex = ($hostelId - 1) % count($allLogos);
                $selectedLogo = $allLogos[$logoIndex];
                $logoPath = $logoDir . $selectedLogo;

                // Database मा update गर्ने (अबको लागि)
                $hostel->logo_path = $logoPath;
                $hostel->save();

                // Base64 मा convert गर्ने
                $fullPath = storage_path('app/public/' . $logoPath);
                if (file_exists($fullPath)) {
                    return $this->imageToBase64($fullPath);
                }
            }

            // 5. Default logo generate गर्ने
            return $this->generateDefaultLogo($hostel->name);
        } catch (\Exception $e) {
            Log::error('Logo Finder Error: ' . $e->getMessage());
            return $this->generateDefaultLogo('Hostel');
        }
    }

    /**
     * Image to Base64 Converter (Robust Version)
     */
    private function imageToBase64($path)
    {
        try {
            if (!file_exists($path)) {
                return null;
            }

            $imageData = @file_get_contents($path);
            if ($imageData === false) {
                return null;
            }

            $base64 = base64_encode($imageData);
            $mimeType = mime_content_type($path);

            return 'data:' . $mimeType . ';base64,' . $base64;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Default Logo Generator (Always Works)
     */
    private function generateDefaultLogo($hostelName)
    {
        $initial = strtoupper(substr(trim($hostelName), 0, 1));
        if (empty($initial)) {
            $initial = 'H';
        }

        $size = 100;

        // Create image
        $image = imagecreatetruecolor($size, $size);

        // Background colors
        $colors = [
            [59, 130, 246],   // Blue
            [16, 185, 129],   // Green
            [245, 158, 11],   // Orange
            [139, 92, 246],   // Purple
            [239, 68, 68],    // Red
            [99, 102, 241],   // Indigo
        ];

        $colorIndex = crc32($hostelName) % count($colors);
        $color = $colors[$colorIndex];

        $bgColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        imagefilledrectangle($image, 0, 0, $size, $size, $bgColor);

        // Add text (using GD built-in font)
        $font = 5; // Built-in GD font
        $textWidth = imagefontwidth($font) * strlen($initial);
        $textHeight = imagefontheight($font);
        $x = ($size - $textWidth) / 2;
        $y = ($size - $textHeight) / 2;

        imagestring($image, $font, $x, $y, $initial, $textColor);

        // Add border
        $borderColor = imagecolorallocate($image, 255, 255, 255);
        imagerectangle($image, 0, 0, $size - 1, $size - 1, $borderColor);

        // Output as base64
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode($imageData);
    }

    /**
     * Logo को लागि TEMPORARY FILE बनाउने (DOMPDF compatible)
     */
    private function getLogoForPDF($hostelId)
    {
        try {
            $hostel = Hostel::find($hostelId);
            if (!$hostel) {
                return $this->createDefaultLogoFile();
            }

            // 1. Database बाट logo path पाउने
            if ($hostel->logo_path) {
                $path = storage_path('app/public/' . $hostel->logo_path);
                if (file_exists($path)) {
                    // 2. Temporary file बनाउने
                    return $this->createTemporaryLogoFile($path);
                }
            }

            // 3. Default logo file बनाउने
            return $this->createDefaultLogoFile($hostel->name);
        } catch (\Exception $e) {
            Log::error('PDF Logo Error: ' . $e->getMessage());
            return $this->createDefaultLogoFile('Hostel');
        }
    }

    private function getLogoUrlForPDF($hostelId)
    {
        // Temporary file बनाउने
        $logoPath = $this->getLogoForPDF($hostelId);

        // Local URL generate गर्ने
        $filename = basename($logoPath);

        // यदि तपाईंको application public directory मा file serve गर्छ भने
        $publicPath = public_path('temp_logos/' . $filename);

        // Ensure directory exists
        if (!is_dir(dirname($publicPath))) {
            mkdir(dirname($publicPath), 0755, true);
        }

        // Copy file to public directory
        copy($logoPath, $publicPath);

        // Return absolute URL
        return url('temp_logos/' . $filename);
    }

    /**
     * Temporary logo file बनाउने (DOMPDF को लागि)
     */
    private function createTemporaryLogoFile($originalPath)
    {
        $tempDir = storage_path('app/temp_logos/');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempFile = $tempDir . 'logo_' . md5_file($originalPath) . '.png';

        // 24 घण्टा भित्रको file मात्र use गर्ने
        if (file_exists($tempFile) && (time() - filemtime($tempFile)) < 86400) {
            return $tempFile;
        }

        // Image resize गर्ने र PNG मा convert गर्ने
        $image = null;
        $mime = mime_content_type($originalPath);

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($originalPath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($originalPath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($originalPath);
                break;
            default:
                return $this->createDefaultLogoFile();
        }

        if (!$image) {
            return $this->createDefaultLogoFile();
        }

        // Resize to max 150x150
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        $maxWidth = 150;
        $maxHeight = 150;

        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        $newWidth = (int)($originalWidth * $ratio);
        $newHeight = (int)($originalHeight * $ratio);

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

        // Transparency handle गर्ने
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
        imagefill($resizedImage, 0, 0, $transparent);

        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        // PNG मा save गर्ने
        imagepng($resizedImage, $tempFile, 9);

        imagedestroy($image);
        imagedestroy($resizedImage);

        return $tempFile;
    }

    /**
     * Default logo file बनाउने
     */
    private function createDefaultLogoFile($hostelName = 'Hostel')
    {
        $tempDir = storage_path('app/temp_logos/');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempFile = $tempDir . 'default_logo_' . md5($hostelName) . '.png';

        if (file_exists($tempFile) && (time() - filemtime($tempFile)) < 86400) {
            return $tempFile;
        }

        $initial = strtoupper(substr(trim($hostelName), 0, 1));
        if (empty($initial)) {
            $initial = 'H';
        }

        $size = 100;
        $image = imagecreatetruecolor($size, $size);

        $colors = [
            [59, 130, 246],   // Blue
            [16, 185, 129],   // Green
            [245, 158, 11],   // Orange
            [139, 92, 246],   // Purple
            [239, 68, 68],    // Red
        ];

        $colorIndex = crc32($hostelName) % count($colors);
        $color = $colors[$colorIndex];

        $bgColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        imagefilledrectangle($image, 0, 0, $size, $size, $bgColor);

        // Text
        $font = 5;
        $textWidth = imagefontwidth($font) * strlen($initial);
        $textHeight = imagefontheight($font);
        $x = ($size - $textWidth) / 2;
        $y = ($size - $textHeight) / 2;

        imagestring($image, $font, $x, $y, $initial, $textColor);

        imagepng($image, $tempFile);
        imagedestroy($image);

        return $tempFile;
    }

    /**
     * Generate Bill PDF
     */
    public function generateBill($id)
    {
        try {
            Log::info('Owner: Generating bill for payment: ' . $id);

            $payment = Payment::with(['student.room', 'hostel'])->findOrFail($id);
            $this->checkPaymentPermission($payment);

            // ✅ NEW: Temporary file system use गर्ने
            $logoPath = $this->getLogoForPDF($payment->hostel_id);
            $logoBase64 = $this->getHostelLogo($payment->hostel_id);

            // File path to absolute URL convert गर्ने
            $logoUrl = 'file://' . str_replace('\\', '/', $logoPath);

            $pdf = Pdf::loadView('pdf.bill', [
                'payment' => $payment,
                'hostel' => $payment->hostel,
                'student' => $payment->student,
                'bill_number' => 'BILL-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'logo_path' => $logoUrl, // ✅ यो नयाँ variable
                'logo_base64' => $logoBase64, // ✅ Fallback को लागि
            ])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'helvetica',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'chroot' => storage_path('app/temp_logos'), // ✅ यो important छ
                ]);

            Log::info('Bill PDF generated successfully');
            return $pdf->stream('bill_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Owner Bill PDF Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'बिल जनरेसन असफल भयो',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate Receipt PDF
     */
    public function generateReceipt($id)
    {
        try {
            Log::info('Owner: Generating receipt for payment: ' . $id);

            $payment = Payment::with(['student.room', 'hostel', 'verifiedBy'])->findOrFail($id);
            $this->checkPaymentPermission($payment);

            // ✅ NEW: Temporary file system use गर्ने
            $logoPath = $this->getLogoForPDF($payment->hostel_id);
            $logoBase64 = $this->getHostelLogo($payment->hostel_id);
            $logoUrl = 'file://' . str_replace('\\', '/', $logoPath);

            $pdf = Pdf::loadView('pdf.receipt', [
                'payment' => $payment,
                'hostel' => $payment->hostel,
                'student' => $payment->student,
                'receipt_number' => 'REC-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'logo_path' => $logoUrl, // ✅ यो नयाँ variable
                'logo_base64' => $logoBase64, // ✅ Fallback को लागि
            ])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'helvetica',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'chroot' => storage_path('app/temp_logos'), // ✅ यो important छ
                ]);

            Log::info('Receipt PDF generated successfully');
            return $pdf->stream('receipt_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Owner Receipt PDF Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'रसिद जनरेसन असफल भयो',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Debug Logo System
     */
    public function debugLogo($hostelId = null)
    {
        try {
            if (!$hostelId) {
                $hostelId = 15; // Sanctuary Girls Hostel
            }

            $hostel = Hostel::find($hostelId);
            $logoBase64 = $this->getHostelLogo($hostelId);

            $html = '<!DOCTYPE html>
            <html>
            <head>
                <title>Logo Debug</title>
                <style>
                    body { font-family: Arial; padding: 20px; }
                    .logo-container { margin: 20px 0; border: 2px solid #ccc; padding: 20px; }
                    .logo-img { max-width: 200px; border: 2px solid green; }
                    .success { color: green; }
                    .error { color: red; }
                </style>
            </head>
            <body>
                <h1>Logo Debug System</h1>
                <div class="logo-container">
                    <h3>Hostel Information:</h3>
                    <p><strong>ID:</strong> ' . $hostelId . '</p>
                    <p><strong>Name:</strong> ' . ($hostel ? $hostel->name : 'N/A') . '</p>
                    <p><strong>Database logo_path:</strong> ' . ($hostel ? $hostel->logo_path : 'NULL') . '</p>
                </div>
                
                <div class="logo-container">
                    <h3>Generated Logo:</h3>';

            if ($logoBase64) {
                $html .= '<p class="success">✅ Logo Generated Successfully!</p>';
                $html .= '<p><strong>Base64 Length:</strong> ' . strlen($logoBase64) . ' characters</p>';
                $html .= '<img class="logo-img" src="' . $logoBase64 . '" alt="Logo">';
            } else {
                $html .= '<p class="error">❌ Logo Generation Failed!</p>';
            }

            $html .= '</div>
                <div class="logo-container">
                    <h3>Test Links:</h3>
                    <p><a href="/owner/payments/2/receipt" target="_blank">Test Receipt for Payment ID 2</a></p>
                    <p><a href="/owner/payments/2/bill" target="_blank">Test Bill for Payment ID 2</a></p>
                </div>
                <div class="logo-container">
                    <h3>Temp Logo Files:</h3>';

            $tempDir = storage_path('app/temp_logos/');
            if (is_dir($tempDir)) {
                $files = glob($tempDir . '*.png');
                $html .= '<p><strong>Found ' . count($files) . ' temp logo files</strong></p>';
                foreach ($files as $file) {
                    $html .= '<p>' . basename($file) . ' (' . round(filesize($file) / 1024, 2) . ' KB, ' .
                        date('Y-m-d H:i:s', filemtime($file)) . ')</p>';
                }
            } else {
                $html .= '<p>No temp logo directory found.</p>';
            }

            $html .= '</div>
            </body>
            </html>';

            return $html;
        } catch (\Exception $e) {
            return response('Debug Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Debug PDF Generation
     */
    public function debugPDF()
    {
        try {
            Log::info('Debug PDF function called');

            // Simple HTML test with Nepali text
            $html = '
            <html>
            <head>
                <title>Test PDF</title>
                <style>
                    body { font-family: "NotoSansDevanagari", Arial; }
                    h1 { color: red; }
                </style>
            </head>
            <body>
                <h1>Test PDF Generation</h1>
                <p>Date: ' . now()->format('Y-m-d H:i:s') . '</p>
                <p>नेपाली टेक्स्ट टेस्ट: १ २ ३ ४ ५ ६ ७ ८ ९ ०</p>
                <p>If you see this, DOMPDF is working!</p>
            </body>
            </html>';

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'NotoSansDevanagari',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

            Log::info('PDF created successfully');
            return $pdf->stream('debug_test.pdf');
        } catch (\Exception $e) {
            Log::error('Debug PDF Error: ' . $e->getMessage());
            return response('PDF Error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Test PDF with logo
     */
    public function testLogoPDF($id)
    {
        try {
            $payment = Payment::with(['student.room', 'hostel'])->findOrFail($id);
            $this->checkPaymentPermission($payment);

            $logoPath = $this->getLogoForPDF($payment->hostel_id);
            $logoUrl = 'file://' . str_replace('\\', '/', $logoPath);
            $logoBase64 = $this->getHostelLogo($payment->hostel_id);

            $pdf = Pdf::loadView('pdf.test_bill', [
                'payment' => $payment,
                'hostel' => $payment->hostel,
                'student' => $payment->student,
                'bill_number' => 'TEST-' . $payment->id,
                'logo_path' => $logoUrl,
                'logo_base64' => $logoBase64,
            ])
                ->setOptions([
                    'chroot' => storage_path('app/temp_logos'),
                ]);

            return $pdf->stream('test_bill_' . $payment->id . '.pdf');
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage(), 500);
        }
    }
}
