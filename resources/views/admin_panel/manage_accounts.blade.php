@extends('layouts.ap_app')

@section('app-name')
    Manage Account
@endsection

@section('custom-style')
    <style type="text/css">
        @media only screen and (max-width: 760px)  {
            /* Force table to not be like tables anymore */
            table, thead, tbody, th, td, tr { 
                display: block; 
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            thead tr { 
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr { border: 1px solid #ccc; }

            td { 
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee; 
                position: relative;
                padding-left: 50%; 
                text-align: right;
            }

            td:before { 
                /* Now like a table header */
                position: absolute;
                /* Top/left values mimic padding */
                top: 18px;
                left: 10px;
                width: 100%; 
                padding-right: 10px; 
                white-space: nowrap;
                text-align: left;
                color: #5983e8;
                font-weight: 500;
            }

            /*
            Label the data
            */

            td:nth-of-type(1):before { content: "SL"; }
            td:nth-of-type(2):before { content: "Account type"; }
            td:nth-of-type(3):before { content: "Account Details"; }
            td:nth-of-type(7):before { content: "Action"; }
        }
    </style>
@endsection

@section('main-content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('/add-account-info') }}" class=" btn btn-primary">
                            <i class="mdi mdi-plus"></i>&nbsp;Add Account Info
                        </a>
                        <div class="clr" style="height: 10px;"></div>
                        <div class="table-responsive">
                            <table class="table table-hover user-accounts-list">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Account type</th>
                                        <th>Account Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($acInfo as $key => $acdtl)
                                    @php
                                        $banking_type = "Personal/Business Banking";
                                        
                                        if($acdtl->banking_type == 1){
                                            $banking_type = "Mobile Banking";
                                        }

                                        if($acdtl->operator == 1){
                                            $operator = "Bkash";
                                        }else if($acdtl->operator == 2){
                                            $operator = "DBBL Rocket";
                                        }else if($acdtl->operator == 3){
                                            $operator = "Nagad";
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $banking_type }}</td>
                                        <td>
                                            @if($acdtl->banking_type == 1)
                                                Mobile A/C No: {{ $acdtl->mbl_ac_no }}<br>
                                                Operator: {{ $operator }}<br>
                                            @else
                                                A/C Title: {{ $acdtl->acc_title }}<br>
                                                A/C No: {{ $acdtl->ac_no }}<br>
                                                Bank name: {{ $acdtl->bank_name }}<br>
                                                Branch name: {{ $acdtl->branch_name }}<br>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- <button type="button" class="btn btn-icons btn-xs btn-rounded btn-info"> -->
                                                <i class="mdi mdi-file-edit"></i>
                                            <!-- </button> -->
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- content-wrapper ends -->
@endsection

@section('custom-script')
<script>
    $(function(){
        $('.user-accounts-list').DataTable( {
            responsive: true
        });
        function ajaxCall(userId)
        {
            $.ajax({
                type: 'POST',
                url: "{{ url('/') }}/confirm-payment",
                data: {'user_id' : userId}, // here $(this) refers to the ajax object not form
                dataType: 'JSON',
                success: function (res) {
                    if(res.status == true){
                        location.reload();
                    }
                }
            });
        }

        $(document).on("click", ".make_confirm", function(e){
            e.preventDefault();
            var userID = $(this).data("user_id");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            ajaxCall(userID);
        });
    })
</script>
@endsection