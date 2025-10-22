@extends('layouts.admin')

@section('title', 'सूचना टेम्प्लेटहरू')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-purple text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-stamp mr-2"></i>सूचना टेम्प्लेटहरू
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach($templates as $template)
                        <div class="col-md-4 mb-4">
                            <div class="card template-card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">{{ $template['name'] }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">{{ $template['description'] }}</p>
                                    <div class="template-preview bg-light p-3 rounded mb-3">
                                        <small>{{ Str::limit($template['content'], 100) }}</small>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-primary btn-sm use-template" 
                                            data-content="{{ $template['content'] }}">
                                        <i class="fas fa-plus mr-1"></i>प्रयोग गर्नुहोस्
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm preview-template"
                                            data-content="{{ $template['content'] }}"
                                            data-title="{{ $template['name'] }}">
                                        <i class="fas fa-eye mr-1"></i>पूर्वावलोकन
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Empty State -->
                    @if(empty($templates))
                    <div class="text-center py-5">
                        <i class="fas fa-stamp text-gray-400 fa-4x mb-3"></i>
                        <h4 class="text-gray-600">कुनै टेम्प्लेट उपलब्ध छैन</h4>
                        <p class="text-gray-500">टेम्प्लेटहरू शीघ्रै थपिनेछन्</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">टेम्प्लेट पूर्वावलोकन</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="templatePreviewContent" class="bg-light p-4 rounded"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">बन्द गर्नुहोस्</button>
                <button type="button" class="btn btn-primary" id="useTemplateBtn">प्रयोग गर्नुहोस्</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview template
    $('.preview-template').click(function() {
        const content = $(this).data('content');
        const title = $(this).data('title');
        
        $('#previewModalLabel').text(title + ' - पूर्वावलोकन');
        $('#templatePreviewContent').text(content);
        $('#useTemplateBtn').data('content', content);
        
        $('#previewModal').modal('show');
    });

    // Use template from preview
    $('#useTemplateBtn').click(function() {
        const content = $(this).data('content');
        window.location.href = "{{ route('admin.circulars.create') }}?template=" + encodeURIComponent(content);
    });

    // Use template directly
    $('.use-template').click(function() {
        const content = $(this).data('content');
        window.location.href = "{{ route('admin.circulars.create') }}?template=" + encodeURIComponent(content);
    });
});
</script>

<style>
.template-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.template-preview {
    border-left: 4px solid #6f42c1;
    background-color: #f8f9fa !important;
}
</style>
@endpush