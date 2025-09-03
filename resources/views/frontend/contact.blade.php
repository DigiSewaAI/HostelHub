@extends('layouts.app')
@section('title','सम्पर्क - HostelHub')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
  <h1 class="text-3xl font-bold mb-6">सम्पर्क गर्नुहोस्</h1>
  @if(session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
  @endif
  <form method="POST" action="{{ route('contact.store') }}">
    @csrf
    <div class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="text-sm">नाम</label>
        <input name="name" class="mt-1 w-full border rounded-lg px-3 py-2" required>
      </div>
      <div>
        <label class="text-sm">इमेल</label>
        <input name="email" type="email" class="mt-1 w-full border rounded-lg px-3 py-2" required>
      </div>
    </div>
    <div class="mt-4">
      <label class="text-sm">सन्देश</label>
      <textarea name="message" rows="5" class="mt-1 w-full border rounded-lg px-3 py-2" required></textarea>
    </div>
    <button class="mt-4 px-4 py-2 rounded-lg bg-gray-900 text-white">पठाउनुहोस्</button>
  </form>
</div>
@endsection