@extends('layouts.owner')

@section('title', 'Settings - HostelHub')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Settings Menu</h3>
                </div>
                <div class="card-body p-0">
                    <div class="nav flex-column nav-pills" id="settings-tab" role="tablist">
                        <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab">
                            <i class="fas fa-cog mr-2"></i>General
                        </a>
                        <a class="nav-link" id="payment-tab" data-toggle="pill" href="#payment" role="tab">
                            <i class="fas fa-credit-card mr-2"></i>Payment
                        </a>
                        <a class="nav-link" id="notification-tab" data-toggle="pill" href="#notification" role="tab">
                            <i class="fas fa-bell mr-2"></i>Notifications
                        </a>
                        <a class="nav-link" id="security-tab" data-toggle="pill" href="#security" role="tab">
                            <i class="fas fa-shield-alt mr-2"></i>Security
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <div class="tab-content" id="settings-tabContent">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">General Settings</h3>
                        </div>
                        <form action="{{ route('owner.settings.general.update') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                </div>
                                <div class="form-group">
                                    <label>Organization Name</label>
                                    <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name', $organization->name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update General Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Payment Settings -->
                <div class="tab-pane fade" id="payment" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Payment Settings</h3>
                        </div>
                        <form action="{{ route('owner.settings.payment.update') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <h5>Bank Transfer</h5>
                                @php
                                    $paymentSettings = $organization->settings['payment'] ?? [];
                                @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $paymentSettings['bank_name'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Account Number</label>
                                            <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $paymentSettings['account_number'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Account Holder Name</label>
                                    <input type="text" name="account_holder" class="form-control" value="{{ old('account_holder', $paymentSettings['account_holder'] ?? '') }}">
                                </div>

                                <hr>
                                <h5>Online Payment</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>eSewa Merchant ID</label>
                                            <input type="text" name="esewa_merchant_id" class="form-control" value="{{ old('esewa_merchant_id', $paymentSettings['esewa_merchant_id'] ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Khalti Merchant ID</label>
                                            <input type="text" name="khalti_merchant_id" class="form-control" value="{{ old('khalti_merchant_id', $paymentSettings['khalti_merchant_id'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update Payment Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="tab-pane fade" id="notification" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Notification Settings</h3>
                        </div>
                        <form action="{{ route('owner.settings.notification.update') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="email_notifications" value="1" class="custom-control-input" id="email_notifications" {{ $user->email_notifications ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="email_notifications">Email Notifications</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="sms_notifications" value="1" class="custom-control-input" id="sms_notifications" {{ $user->sms_notifications ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="sms_notifications">SMS Notifications</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="booking_alerts" value="1" class="custom-control-input" id="booking_alerts" {{ $user->booking_alerts ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="booking_alerts">Booking Alerts</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="payment_alerts" value="1" class="custom-control-input" id="payment_alerts" {{ $user->payment_alerts ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="payment_alerts">Payment Alerts</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update Notification Settings</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Security Settings</h3>
                        </div>
                        <form action="{{ route('owner.settings.security.update') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                    @error('current_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                    @error('new_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle tab persistence
        $('a[data-toggle="pill"]').on('click', function() {
            localStorage.setItem('activeSettingsTab', $(this).attr('href'));
        });

        var activeTab = localStorage.getItem('activeSettingsTab');
        if (activeTab) {
            $('.nav-pills a[href="' + activeTab + '"]').tab('show');
        }
    });
</script>
@endsection