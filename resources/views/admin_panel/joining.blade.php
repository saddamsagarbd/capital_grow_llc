@extends('layouts.ap_app')

@section('app-name')
    Joining
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
                        <hr>
                        <h4 class="card-description">Personal Information</h4>
                        <hr>
                        <!-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <hr>
                        @endif -->
                        <form class="form-sample" action="{{ route('make-user-joining') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Reference User</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="" name="refer_username" class="form-control refer_username" data-for="referral" required="">
                                            <span class="referral_name"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Placement User</label>
                                        <div class="col-sm-9">
                                            <input type="text" value="" name="placement_username" class="form-control placement_username" data-for="placement" required="">
                                            <span class="placement_name"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            <button type="submit" class="btn btn-success mr-2">Submit</button>
                            <button type="reset" class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- content-wrapper ends -->	
@endsection
@section('custom-script')
<script type="text/javascript">
    $(function(){
        function ajaxCall(username, msgfor)
        {
            $.ajax({
                type: 'POST',
                url: "{{ url('/') }}/get-referral-name",
                data: {'username' : username}, // here $(this) refers to the ajax object not form
                dataType: 'JSON',
                success: function (res) {
                    console.log(res);
                    var msg = "";
                    
                    $(document).find('span.'+ msgfor +'_name').html('');
                    
                    if( typeof res === 'undefined' || res === null ){
                        msg = "User not found/exist";
                    }else{
                        var fullName = res.first_name +" "+ res.last_name;
                        msg = '['+ fullName +']';
                    }
                    
                    $(document).find('span.'+ msgfor +'_name').html(msg);
                },
            });
        }
        $(document).on("change", ".refer_username, .placement_username", function(e){
            e.preventDefault();
            var username = $(this).val();
            var _for = $(this).data("for");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if(username.length > 3){
                ajaxCall(username, _for);
            }

        });

    });
</script>
@endsection