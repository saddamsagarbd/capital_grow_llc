@extends('layouts.ap_app')

@section('app-name')
    Add Account Info
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
                        <h4 class="card-description">Personal Account Information</h4>
                        <hr>
                        <form class="form-sample" action="{{ route('save-account-info') }}" method="post" enctype="multipart/form-data">
                            @csrf
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
                            </div>
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

                            <button type="submit" class="btn btn-success mr-2 submitBtn">Submit</button>
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