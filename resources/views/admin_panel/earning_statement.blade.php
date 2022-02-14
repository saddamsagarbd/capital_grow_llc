@extends('layouts.ap_app')

@section('app-name')
    Earning Statement
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
            /* td:nth-of-type(1):before { content: "SL"; } */
            td:nth-of-type(1):before { content: "Date"; }
            td:nth-of-type(2):before { content: "Decription"; }
            td:nth-of-type(3):before { content: "Debit (Db.)"; }
            td:nth-of-type(4):before { content: "Credit (Cr.)"; }
            td:nth-of-type(5):before { content: "Balance"; }
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
                            <table class="table table-hover income-statement">
                                <thead>
                                    <tr>
                                        <!-- <th style="width: 5%">SL</th> -->
                                        <th style="width: 10%">Date</th>
                                        <th style="width: 40%">Decription</th>
                                        <th>Debit (Db.)</th>
                                        <th>Credit (Cr.)</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($statements as $key => $statement)
                                    <tr>
                                        <!-- <td>{{ ++$key }}</td> -->
                                        <td>{{ date('d-m-Y', strtotime($statement->created_at)) }}</td>
                                        <td>{{ $statement->trxn_details }}
                                            <br/>transaction ref: {{ $statement->trxn_id }}</td>
                                        <td>{{ ($statement->debit == null)?'--':$statement->debit }}</td>
                                        <td>{{ ($statement->credit == null)?'--':"$".$statement->credit }}</td>
                                        <td>{{ ($statement->balance == null)?'$0.00':"$".$statement->balance }}</td>
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
<script type="text/javascript">
    $(function(){
        $('.income-statement').DataTable( {
            responsive: true
        });
    })
</script>
@endsection