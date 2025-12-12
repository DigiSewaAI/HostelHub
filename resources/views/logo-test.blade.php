<!DOCTYPE html>
<html>
<head>
    <title>Logo Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .box { border: 2px solid #ccc; padding: 15px; margin: 10px 0; }
        .success { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
        img { max-width: 300px; margin: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>🔍 Logo Debug Information</h1>
    
    <div class="box {{ $logoExists ? 'success' : 'error' }}">
        <h2>Hostel Information</h2>
        <p><strong>Name:</strong> {{ $hostel->name }}</p>
        <p><strong>Logo Path in DB:</strong> {{ $hostel->logo_path }}</p>
        <p><strong>Full Storage Path:</strong> {{ $logoPath }}</p>
        <p><strong>File Exists:</strong> 
            <span style="color: {{ $logoExists ? 'green' : 'red' }}; font-weight: bold;">
                {{ $logoExists ? '✅ YES' : '❌ NO' }}
            </span>
        </p>
    </div>
    
    @if($logoExists)
    <div class="box">
        <h2>📸 Image Preview - FILE URL Method</h2>
        <p><strong>File URL:</strong> {{ $fileUrl }}</p>
        <p><strong>Preview:</strong></p>
        <img src="{{ $fileUrl }}" alt="File URL Preview">
        <p><em>If broken image shows above, file:// URL not working</em></p>
    </div>
    
    <div class="box">
        <h2>📸 Image Preview - BASE64 Method</h2>
        <img src="{{ $base64 }}" alt="Base64 Preview">
        <p><em>If this shows, use base64 method in PDF</em></p>
    </div>
    
    <div class="box">
        <h2>🚀 Quick Actions</h2>
        <p>
            <a href="/fix-logo/{{ $hostel->id }}" style="color: orange; margin-right: 15px;">
                🔧 Fix Current Logo
            </a>
            <a href="/create-svg-logo/{{ $hostel->id }}" style="color: green;">
                🎨 Create New SVG Logo
            </a>
        </p>
    </div>
    @else
    <div class="box error">
        <h2>❌ Logo File Not Found</h2>
        <p>Check these:</p>
        <ol>
            <li>Run: <code>php artisan storage:link</code></li>
            <li>Check storage/app/public/hostel_logos/ folder</li>
            <li>Verify file name matches database</li>
        </ol>
    </div>
    @endif
    
    <div class="box">
        <h2>📋 Testing Links</h2>
        <ul>
            <li><a href="/owner/payments/2/receipt" target="_blank">Test Receipt PDF (Owner)</a></li>
            <li><a href="/owner/payments/2/bill" target="_blank">Test Bill PDF (Owner)</a></li>
            <li><a href="/admin/payments/2/receipt" target="_blank">Test Receipt PDF (Admin)</a></li>
            <li><a href="/admin/payments/2/bill" target="_blank">Test Bill PDF (Admin)</a></li>
        </ul>
    </div>
</body>
</html>