@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">होस्टल म्यानेजर ड्यासबोर्ड</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    तपाईं होस्टल म्यानेजर (Owner) रूपमा लगइन गर्नुभयो!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection