@extends('layouts.admin')

@section('शीर्षक', 'सम्पर्क विवरण')

@section('विस्तार')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">सम्पर्क विवरण</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%;">नाम:</th>
                                    <td>{{ $submission->name }}</td>
                                </tr>
                                <tr>
                                    <th>इमेल:</th>
                                    <td>{{ $submission->email }}</td>
                                </tr>
                                <tr>
                                    <th>फोन:</th>
                                    <td>{{ $submission->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>विषय:</th>
                                    <td>{{ $submission->subject }}</td>
                                </tr>
                                <tr>
                                    <th>स्थिति:</th>
                                    <td>
                                        <form action="{{ route('admin.contacts.updateStatus', $submission->id) }}" method="POST" class="form-inline">
                                            @csrf
                                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <option value="नयाँ" {{ $submission->status == 'नयाँ' ? 'selected' : '' }}>नयाँ</option>
                                                <option value="पढियो" {{ $submission->status == 'पढियो' ? 'selected' : '' }}>पढियो</option>
                                                <option value="जवाफ दिइयो" {{ $submission->status == 'जवाफ दिइयो' ? 'selected' : '' }}>जवाफ दिइयो</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <th>पठाइएको मिति:</th>
                                    <td>{{ $submission->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>सन्देश:</h5>
                            <p>{{ $submission->message }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.contacts.edit', $submission->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> सम्पादन गर्नुहोस्
                    </a>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left"></i> सम्पर्क सूचीमा फर्कनुहोस्
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection