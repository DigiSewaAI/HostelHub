<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>{{ url('/') }}</loc></url>
  <url><loc>{{ route('features') }}</loc></url>
  <url><loc>{{ route('how-it-works') }}</loc></url>
  <url><loc>{{ route('pricing') }}</loc></url>
  <url><loc>{{ route('gallery.public') }}</loc></url>
  <url><loc>{{ route('reviews') }}</loc></url>
  <url><loc>{{ route('contact') }}</loc></url>
  <url><loc>{{ route('privacy') }}</loc></url>
  <url><loc>{{ route('terms') }}</loc></url>
  <url><loc>{{ route('cookies') }}</loc></url>
</urlset>