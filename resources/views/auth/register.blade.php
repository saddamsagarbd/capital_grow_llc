@extends('layouts.app')
@section('app-name')
    {{ __('Register | Capital Grow LLC') }}
@endsection
@section('auth-content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
      <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
        <div class="row w-100">
          <div class="col-lg-9 mx-auto" style="text-align:center;">
            <!-- <h2 class="text-center mb-4">{{ __('Capital Grow LLC') }}</h2> -->
            <img src="{{ asset('images/logo/capital-grow-02.png') }}" width="400px" style="margin-bottom: 20px;" alt="" srcset="">
            <div class="auto-form-wrapper">
              <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                <h4 class="card-description">Personal Information</h4>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">First Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" name="first_name" placeholder="e.g. John" required autocomplete="first_name">
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Last Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('last_name') }}" name="last_name" placeholder="e.g. Smith" required autocomplete="last_name">
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Email</label>
                      <div class="col-sm-9">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" placeholder="e.g johnsmith@gmail.com" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Username</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" name="username" placeholder="e.g johnsmith007" required autocomplete="username">
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Password</label>
                      <div class="col-sm-9">
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" name="password" required autocomplete="new-password" placeholder="********">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Confirm Password</label>
                      <div class="col-sm-9">
                        <!-- <input type="password" class="form-control" placeholder="********"> -->
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="********" autocomplete="new-password">
                        <span class="invalid-feedback display-none" role="alert">
                            <strong>Password does not match</strong>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Contact No</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror" value="{{ old('contact_number') }}" name="contact_number" placeholder="e.g. +1-541-754-3010" required autocomplete="contact_number">
                        @error('contact_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Gender</label>
                      <div class="col-sm-9">
                        <select class="form-control @error('gender') is-invalid @enderror" name="gender">
                          @foreach(get_gender_list() as $key => $gender)
                            <option value="{{ $key }}">{{ $gender }}</option>
                          @endforeach
                        </select>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Identification No</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control @error('identification_no') is-invalid @enderror" value="{{ old('identification_no') }}" name="identification_no" placeholder="e.g. 5417543010" required autocomplete="id_no">
                        @error('identification_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Date of Birth</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}" name="dob" placeholder="e.g. dd/mm/yy" required autocomplete="dob">
                        @error('dob')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>

                <hr>

                <h4 class="card-description">Upload Profile Image</h4>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-sm-3 col-form-label">File upload</label>
                      <input type="file" name="img[]" class="file-upload-default">
                      <div class="input-group col-sm-9">
                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                        <span class="input-group-append">
                            <button class="file-upload-browse btn btn-info" type="button">{{ __('Upload') }}</button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group row">
                      <div class="preview_image">

                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 offset-md-3">
                    <div class="form-group row">
                      <button class="btn btn-primary submit-btn btn-block">{{ __('Register') }}</button>
                    </div>
                  </div>
                </div>

                
                <div class="text-block text-center my-3">
                  <span class="text-small font-weight-semibold">{{ __('Go to website') }} ?</span>
                  <a href="{{ url('/') }}" class="text-black text-small">{{ __('Click Here') }}!</a>
                </div>

                <div class="text-block text-center my-3">
                  <span class="text-small font-weight-semibold">{{ __('Already have and account') }} ?</span>
                  <a href="{{ url('/user-login') }}" class="text-black text-small">{{ __('Login') }}</a>
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