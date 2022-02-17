@extends('layouts.ap_app')

@section('app-name')
    Payment Confirm
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
                        <h4 class="card-description">Payment Confirmation Form</h4>
                        <hr>
                        @if($payment_request == 0)
                            <div class="alert alert-success">
                                Thank you ! We have received your request, this account will be activated very soon.
                            </div>
                        @endif
                        @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('success') }}
                            </div>
                            <hr>
                        @endif

                        @if(session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                            <hr>
                        @endif
                        @if($payment_request === '')
                            <form class="form-sample" action="{{ route('make-payment-confirm') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 col-form-label">Username</label>
                                            <div class="col-sm-12 ">
                                                <input type="text" value="{{ Auth::user()->username }}" name="username" class="form-control username" readonly required="">
                                                <span class="username"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 col-form-label">Transaction No</label>
                                            <div class="col-sm-12 ">
                                                <input type="text" value="" name="transaction_no" class="form-control transaction_no" required="">
                                                <span class="transaction_no"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 col-form-label">Payment Option</label>
                                            <div class="col-sm-12 ">
                                                <select class="form-control @error('payment_option') is-invalid @enderror" name="payment_option">
                                                    <option value="1">bKash</option>
                                                    <option value="2">Rocket</option>
                                                    <option value="3">Nagad</option>
                                                    <option value="4">Bank Deposite</option>
                                                </select>
                                                @error('payment_option')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 col-form-label">Mobile/Account No <small>(Sender account number)</small></label>
                                            <div class="col-sm-12 ">
                                                <input type="text" class="form-control @error('pay_to_ac') is-invalid @enderror" value="{{ old('pay_to_ac') }}" name="pay_to_ac" placeholder="e.g. 01XXXXXXXXXX / 123.4567.890" required>
                                                @error('pay_to_ac')
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
                                        <div class="form-group">
                                            <label class="col-sm-12 col-form-label">Paid Amount</label>
                                            <div class="col-sm-12 ">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" min="185" class="form-control @error('paid_amount') is-invalid @enderror" value="{{ old('paid_amount') }}" name="paid_amount" placeholder="e.g. 185" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">.00</span>
                                                    </div>
                                                </div>
                                                @error('paid_amount')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 col-form-label">Payment Date</label>
                                            <div class="col-sm-12 ">
                                                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date') }}" name="payment_date" placeholder="e.g. dd/mm/yy" required>
                                                @error('payment_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h4 class="card-description">Upload Payment Slip <small>(option)</small></h4>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-sm-12 col-form-label">File upload</label>
                                        <input type="file" name="img[]" class="file-upload-default">
                                        <div class="input-group col-sm-12 ">
                                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-info" type="button">{{ __('Upload') }}</button>
                                            </span>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <div class="preview_image">

                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mr-2">Submit</button>
                                <button type="reset" class="btn btn-light">Cancel</button>
                            </form>
                        @endif
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