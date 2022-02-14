@extends('layouts.ap_app')

@section('app-name')
    Balance Withdrawal
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
                        <h4 class="card-description">Withdrawal Information</h4>
                        <div class="alert alert-danger error-msg" style="display:none;">
                            
                        </div>
                        <hr>
                        <h4>Total Balance: ${{ isset($balance)?$balance:'0.00' }}</h4>
                        <p style="color: #fe7758 !important; font-size: 13px; font-weight: 500 !important; line-height: 23px !important;">Note: Vat(10%) and Tax(5%) charge will be added with Transfer/Withdrawal amount</p>
                        <div class="clr" style="height:20px;"></div>

                        @if(session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                            <hr>
                        @endif

                        @if($existingWithdrawalRequest == true)
                            <div class="alert alert-warning">
                                You already have a pending request for withdrawal. You can able to apply for request after accept/decline your request. Thank you.
                            </div>
                            <hr>
                        @else
                        
                        <form class="form-sample" action="{{ route('withdrawal-balance') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="balance" value="{{ isset($balance)?$balance:0 }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Amount</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control withdrawal_amount @error('withdrawal_amount') is-invalid @enderror" value="{{ old('withdrawal_amount') }}" name="withdrawal_amount" placeholder="e.g. 185" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                            <!-- <input type="text" class="form-control username @error('username') is-invalid @enderror" value="{{ old('username') }}" name="username" placeholder="e.g johndoe" required> -->
                                            @error('withdrawal_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Vat(10%)</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control vat_amount @error('vat_amount') is-invalid @enderror" value="{{ old('vat_amount') }}" name="vat_amount" readonly required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                            <!-- <input type="text" class="form-control username @error('username') is-invalid @enderror" value="{{ old('username') }}" name="username" placeholder="e.g johndoe" required> -->
                                            @error('vat_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Tax(5%)</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>                                                
                                                <input type="number" class="form-control tax_amount @error('tax_amount') is-invalid @enderror" value="{{ old('tax_amount') }}" readonly name="tax_amount" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                            @error('tax_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Total</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control total_amount @error('total_amount') is-invalid @enderror" value="{{ old('total_amount') }}" name="total_amount" readonly required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                            <!-- <input type="text" class="form-control username @error('username') is-invalid @enderror" value="{{ old('username') }}" name="username" placeholder="e.g johndoe" required> -->
                                            @error('total_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h4 class="card-description">Receivable Account Information</h4>
                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Banking Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control banking_type @error('banking_type') is-invalid @enderror" name="banking_type">
                                                <option value="1">Mobile Banking</option>
                                                <option value="2">Personal/Business Banking</option>
                                            </select>
                                            @error('banking_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="clr"></div>
                            </div>
                            
                            <div class="clr"></div>
                            
                            <div class="row" id="indv-bus-banking" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Account Title</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('acc_title') is-invalid @enderror" value="{{ old('acc_title') }}" name="acc_title" placeholder="e.g. John Doe">
                                        @error('acc_title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Account No</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control ac_no @error('ac_no') is-invalid @enderror" value="{{ old('ac_no') }}" name="ac_no" placeholder="e.g. XXX.XXXX.XXX">
                                        @error('ac_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Bank Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}" name="bank_name" placeholder="e.g. Bank Name Here">
                                        @error('bank_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Branch Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control @error('branch_name') is-invalid @enderror" value="{{ old('branch_name') }}" name="branch_name" placeholder="e.g. Branch Name Here">
                                        @error('branch_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="mobile-banking">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Operator</label>
                                        <div class="col-sm-9">
                                            <select class="form-control operator @error('operator') is-invalid @enderror" name="operator" required>
                                                <option value="1">BKash</option>
                                                <option value="2">DBBL Rocket</option>
                                                <option value="3">Nagad</option>
                                            </select>
                                            @error('operator')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Mobile A/C No</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control mbl_ac_no @error('mbl_ac_no') is-invalid @enderror" value="{{ old('mbl_ac_no') }}" name="mbl_ac_no" placeholder="e.g 01XXX XXX XXX" required>
                                        @error('mbl_ac_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Account type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ac_type @error('ac_type') is-invalid @enderror" name="ac_type" required>
                                                <option value="1">Personal</option>
                                                <option value="2">Agent</option>
                                                <option value="3">Marchent</option>
                                            </select>
                                            @error('ac_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="otp-section" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">OTP</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control otp @error('otp') is-invalid @enderror" value="{{ old('otp') }}" name="otp" placeholder="e.g johndoe">
                                            @error('otp')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success mr-2 nextBtn" data-user_id="{{ Auth::user()->id }}">Next</button>
                            <button type="submit" class="btn btn-success mr-2 submitBtn" style="display: none;">Submit</button>
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
        $(".submitBtn").hide();
        $(document).on("change", ".withdrawal_amount", function(){
            var wAmt = $(this).val();
            var vatAmt = parseFloat(parseFloat(wAmt) * 0.1);
            var taxAmt = parseFloat(parseFloat(wAmt) * 0.05);
            var total = parseFloat(parseFloat(wAmt) + parseFloat(vatAmt) + parseFloat(taxAmt));

            var prevBalance = $("input[name='balance']").val();

            $(".error-msg").attr('style', 'display: none');
            $(".error-msg").html('');
            $(".nextBtn").prop("disabled", false);

            if(total > parseFloat(prevBalance)){
                $(".error-msg").removeAttr("style");
                $(".error-msg").html("Withdrawal amount can not be greater then balance amount.");
                $(".nextBtn").prop("disabled", true);
            }

            $(".vat_amount").val(vatAmt);
            $(".tax_amount").val(taxAmt);
            $(".total_amount").val(total);
        });

        function sendOtp(id)
        {
            $.ajax({
                type: 'POST',
                url: "{{ url('/') }}/send-otp",
                data: {'id' : id}, // here $(this) refers to the ajax object not form
                dataType: 'JSON',
                success: function (res) {

                    $("#otp-section").hide();
                    $(".error-msg").attr('style', 'display: none');
                    $(".error-msg").html('');
                    $(".nextBtn").show();
                    $(".submitBtn").hide();
                    if(res.status == true){
                        $("#otp-section").show();
                        $(".nextBtn").hide();
                        $(".submitBtn").show();
                    }else{
                        $(".error-msg").removeAttr("style");
                        $(".error-msg").html("OTP not sent. Please try after sometime!");
                    }
                },
            });
        }

        $(document).on("click", ".nextBtn", function(e){

            var id = $(this).data('user_id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            sendOtp(id);
        });

                /**
            checkMblAccountExist() has two params mobile account number and second one is account operator
        */
        function checkMblAccountExist(mblAcNo, acOpt)
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

        $(document).on("change", ".mbl_ac_no", function(e){
            e.preventDefault();
            $(document).find(".submitBtn").prop("disabled", false);
            var mblAcNo = $(this).val();
            var acOpt = $(".operator").val();
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if(mblAcNo.length > 11 || mblAcNo.length < 11){
                alert("Mobile Account Number must be 11 digit");
                $(document).find(".submitBtn").prop("disabled", true);
                return false;
            }

            if(mblAcNo.length == 11){
                checkMblAccountExist(mblAcNo, acOpt);
            }

        });

        $(document).on("change", ".banking_type", function(){
            $(document).find("#mobile-banking").hide();
            $(document).find("#mobile-banking :not(:checkbox)").prop("required", false);

            $(document).find("#indv-bus-banking").hide();
            $(document).find("#indv-bus-banking :not(:checkbox)").prop("required", false);
            
            if($(this).val() == 1){
                $(document).find("#mobile-banking").show();
                $(document).find("#mobile-banking :not(:checkbox)").prop("required", true);
            }else if($(this).val() == 2){
                $(document).find("#indv-bus-banking").show();
                $(document).find("#indv-bus-banking :not(:checkbox)").prop("required", true);
            }
        });
    });
</script>
@endsection