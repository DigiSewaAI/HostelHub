@extends('layouts.admin')

@section('शीर्षक', 'सम्पर्क सम्पादन गर्नुहोस्')

@section('विस्तार')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">सम्पर्क सम्पादन गर्नुहोस्</h3>
                </div>

                <form action="{{ route('admin.contacts.update', $contact->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">पूरा नाम</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $contact->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">इमेल ठेगाना</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $contact->email }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">फोन नम्बर</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $contact->phone }}">
                        </div>

                        <div class="form-group">
                            <label for="subject">विषय</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="{{ $contact->subject }}" required>
                        </div>

                        <div class="form-group">
                            <label for="message">सन्देश</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required>{{ $contact->message }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> परिवर्तनहरू सुरक्षित गर्नुहोस्
                        </button>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i> रद्द गर्नुहोस्
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection