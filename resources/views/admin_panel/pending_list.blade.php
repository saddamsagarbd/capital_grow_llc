@extends('layouts.ap_app')

@section('app-name')
    Pending User List
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
        td:nth-of-type(2):before { content: "Username"; }
        td:nth-of-type(3):before { content: "Details"; }
        td:nth-of-type(4):before { content: "Amount ($)"; }
        td:nth-of-type(5):before { content: "Payment Date"; }
        td:nth-of-type(6):before { content: "Status"; }
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
                        <div class="table-responsive">
                            <table class="table table-hover pending-users-list">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">SL</th>
                                        <th>Username</th>
                                        <th style="width: 25%">Details</th>
                                        <th>Amount ($)</th>
                                        <th>Payment Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            <a href="{{ url('/user-details/'.$user->user_id) }}">
                                                {{ $user->username }}
                                            </a>
                                        </td>
                                        <td>
                                            <?php
                                                $pm = "Bkash";
                                                if($user->pay_to == 2){
                                                    $pm = "DBBL Rocket";
                                                } else if($user->pay_to == 3){
                                                    $pm = "Nagad";
                                                } else if($user->pay_to == 4){
                                                    $pm = "Bank Deposite";
                                                }
                                            
                                            ?>
                                            Payment made in <strong>{{ $pm }}</strong></br>
                                            on Ac/No <strong>{{ $user->pay_to_ac }}</strong></br>
                                            trxn no is <strong>{{ $user->transaction_no }}</strong>
                                        </td>
                                        <td>{{ "$".number_format($user->paid_amount,2,".","") }}</td>
                                        <td>{{ date("d-m-Y", strtotime($user->payment_date)) }}</td>
                                        <td>
                                            <label class="badge badge-danger">Pending</label>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-xs make_confirm" data-user_id="{{ $user->user_id }}">Confirm</button>
                                            <button class="btn btn-info btn-xs send_payinfo" data-user_id="{{ $user->user_id }}">
                                                <i class="fa fa-paper-plane fa-fw"></i> Send Payment Info
                                            </button>
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
        $('.pending-users-list').DataTable( {
            responsive: true
        });
        function doAction(userId, __for)
        {
            $.ajax({
                type: 'POST',
                url: "{{ url('/') }}/confirm-payment",
                data: {'user_id' : userId, 'for': __for}, // here $(this) refers to the ajax object not form
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
            $(this).prop('disabled', true);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            doAction(userID, "confirm_payment");
        });

        $(document).on("click", ".send_payinfo", function(e){
            e.preventDefault();
            var userID = $(this).data("user_id");
            $(this).prop('disabled', true);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            doAction(userID, "send_payinfo");
        });
    })
</script>
@endsection