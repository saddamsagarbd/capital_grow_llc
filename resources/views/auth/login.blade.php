@extends('layouts.app')
@section('app-name')
    {{ __('Login | Capital Grow LLC') }}
@endsection
@section('auth-content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
      <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auto-form-wrapper" style="text-align: center">
              <img src="{{ asset('images/logo/capital-grow-02.png') }}" width="320px" style="margin-bottom: 20px;" alt="" srcset="">
              <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group" style="text-align: left">
                  <label class="label">Username/Email</label>
                  <div class="input-group">
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Enter Username or Email" value="{{ old('username') }}" required autocomplete="email" autofocus>
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                    @error('username')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>
                <div class="form-group" style="text-align: left">
                  <label class="label">Password</label>
                  <div class="input-group">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="*********" value="{{ old('password') }}" required autocomplete="current-password" />
                    <div class="input-group-append">
                      <span class="input-group-text">
                        <i class="mdi mdi-check-circle-outline"></i>
                      </span>
                    </div>
                    @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block">Login</button>
                </div>
                <div class="form-group d-flex justify-content-between">
                  <div class="form-check form-check-flat mt-0">
                    <!-- <label class="form-check-label">
                      <input type="checkbox" class="form-check-input" checked> Keep me signed in
                    </label> -->
                  </div>
                  <a href="#" class="text-small forgot-password text-black">{{ __('Forgot Password') }}</a>
                </div>
                <div class="text-block text-center my-3">
                  <span class="text-small font-weight-semibold">{{ __('Go to website') }} ?</span>
                  <a href="{{ url('/') }}" class="text-black text-small">{{ __('Click Here') }}!</a>
                </div>
                <div class="text-block text-center my-3">
                  <span class="text-small font-weight-semibold">{{ __('Not a member') }} ?</span>
                  <a href="{{ route('register') }}" class="text-black text-small">{{ __('Create new account') }}</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
@endsection
