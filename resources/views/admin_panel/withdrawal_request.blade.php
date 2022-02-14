@extends('layouts.ap_app')

@section('app-name')
    Withdrawal Request
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
        td:nth-of-type(2):before { content: "Request Date"; }
        td:nth-of-type(3):before { content: "Amount"; }
        td:nth-of-type(4):before { content: "Withdrawal Details"; }
        td:nth-of-type(5):before { content: "Status"; }
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
                                        <th>Request Date</th>
                                        <th>Amount</th>
                                        <th>Withdrawal Details</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($wRequests as $key => $wRequest)

                                <?php
                                    $wdetails = json_decode($wRequest->withdrawal_details, true);

                                    if($wdetails['operator'] == 1)
                                    {
                                        $operator = "Bkash";
                                    }elseif($wdetails['operator'] == 2){
                                        $operator = "DBBL Rocket";
                                    }elseif($wdetails['operator'] == 3){
                                        $operator = "Nagad";
                                    }

                                    $ac_no = isset($wdetails["mbl_ac_no"])?$wdetails["mbl_ac_no"]:(isset($wdetails["ac_no"])?$wdetails["ac_no"]:'');

                                    if($wdetails['ac_type'] == 1)
                                    {
                                        $ac_type = "Personal";
                                    }
                                    elseif($wdetails['ac_type'] == 2){
                                        $ac_type = "Agent";
                                    }
                                    elseif($wdetails['ac_type'] == 3){
                                        $ac_type = "Merchant";
                                    }
                                ?>
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            {{ date('d-m-Y h:i:s A', strtotime($wRequest->created_at)) }}
                                        </td>
                                        <td>{{ "$".number_format($wRequest->withdrawal_amount,2,".","") }}</td>
                                        <td>
                                            @if(isset($wdetails['operator']))
                                                Media: {{ $operator }}<br/>
                                                Ac No: {{ $ac_no }}<br/>
                                                Account Type: {{ $ac_type }}
                                            @else
                                                Account title: {{ $operator }}<br/>
                                                Ac No: {{ $ac_no }}<br/>
                                                Bank Name: {{ $ac_type }}<br/>
                                                Branch Name: {{ $ac_type }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($wRequest->request_status == 2)
                                            <label class="badge badge-primary">Pending</label>
                                            @elseif($wRequest->request_status == 1)
                                            <label class="badge badge-success">Accept</label>
                                            @else
                                            <label class="badge badge-danger">Declined</label>
                                            @endif
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

</script>
@endsection