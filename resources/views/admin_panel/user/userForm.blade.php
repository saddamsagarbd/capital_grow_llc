@extends('layouts.ap_app')

@section('app-name')
    User Form
@endsection

@section('custom-style')
@endsection
@section('main-content')	
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body pending-user-form">
                        <h4 class="card-title">Personal info</h4>
                        <form class="form-sample" action="{{ route('update-user-info') }}" method="post">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $userDtl->user_id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <img class="img-md profile-img" src="{{ URL::to('/uploads/profile_images/'.$userDtl->profile_img) }}" alt="Profile image">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Username</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="{{ $userDtl->username }}" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">First Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="{{ $userDtl->first_name }}" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Last Name</label>
                                        <div class="col-sm-9">
                                        <input type="text" value="{{ $userDtl->last_name }}" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="{{ $userDtl->email }}" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Contact No</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="{{ $userDtl->contact_number }}" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Reg. Balance</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" name="registration_balance" readonly value="185" min="185" max="185" class="form-control" aria-label="Amount (to the nearest dollar)">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Payment Status</label>
                                        <div class="col-sm-4">
                                            <div class="form-radio">
                                                <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="payment_status" id="membershipRadios1" value="paid"> Paid
                                                <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-radio">
                                                <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="payment_status" id="membershipRadios2" value="due" checked=""> Due
                                                <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">User Status</label>
                                        <div class="col-sm-4">
                                            <div class="form-radio">
                                                <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="user_status" id="membershipRadios1" value="1" checked=""> Active
                                                <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-radio">
                                                <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="user_status" id="membershipRadios2" value="2"> Suspend
                                                <i class="input-helper"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                            <button class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- content-wrapper ends -->	
@endsection
@section('custom-script')

@endsection