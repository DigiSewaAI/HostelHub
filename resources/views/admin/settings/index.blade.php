@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center mb-0">सेटिङ्हरू व्यवस्थापन</h3>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">सेटिङ्हरूको सूची</h5>
                        <a href="{{ route('admin.settings.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> नयाँ सेटिङ्ग थप्नुहोस्
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 5%;">क्रम संख्या</th>
                                    <th>सेटिङ्गको नाम</th>
                                    <th>मान</th>
                                    <th>विवरण</th>
                                    <th class="text-center" style="width: 20%;">क्रियाहरू</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td><strong>{{ $setting->name }}</strong></td>
                                    <td>{{ Str::limit($setting->value, 50) }}</td>
                                    <td>{{ Str::limit($setting->description, 80) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.settings.show', $setting->id) }}" class="btn btn-info btn-sm me-1" title="हेर्नुहोस्">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.settings.edit', $setting->id) }}" class="btn btn-primary btn-sm me-1" title="सम्पादन गर्नुहोस्">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.settings.destroy', $setting->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="मेटाउनुहोस्"
                                                onclick="return confirm('के तपाइँ यो सेटिङ्ग मेटाउन निश्चित हुनुहुन्छ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-exclamation-circle text-muted me-2"></i>
                                        कुनै सेटिङ्हरू फेला परेनन्
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($settings->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $settings->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection