@extends('layouts.ap_app')

@section('app-name')
    Referal User
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
            td:nth-of-type(3):before { content: "Full Name"; }
            td:nth-of-type(4):before { content: "Contact No"; }
            td:nth-of-type(5):before { content: "Email"; }
            td:nth-of-type(6):before { content: "Status"; }
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
                        <ul class="list-arrow">
                            @if(count($users) > 0)
                                @foreach( $users as $key => $user )
                                    <li class="tree-li">
                                        <a href="#" class="child-expand" data-p_id="{{ $user->uid }}">
                                            <i class="fa fa-caret-right tree-right-arrow"></i>{{ $user->first_name }} {{ $user->last_name }}
                                        </a>
                                        <ul class="list-arrow">
                                            <div class="clr"></div>
                                        </ul>
                                    </li>
                                @endforeach
                            @else
                            <li>No users found</li>
                            @endif
                        </ul>
                        <!-- <div class="table-responsive">
                            <table class="table table-hover pending-users-list">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Username</th>
                                        <th>Full Name</th>
                                        <th>Contact No</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td>{{ $user->contact_number }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <button type="button" class="btn btn-icons btn-rounded btn-info">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- content-wrapper ends -->
@endsection

@section('custom-script')
<script>
    $(document).ready(function(){
        // $(document).on("click", ".tree-right-arrow", function(){
        $(document).on("click", ".child-expand", function(e){
            e.preventDefault();

            if($(this).children('i').hasClass("fa-caret-right"))
            {
                $(this).children('i').addClass("fa-caret-down");
                $(this).children('i').removeClass("fa-caret-right");

                var placementId = $(this).data('p_id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var parent = $(this).parent("li").find('ul.list-arrow');

                parent.html('');

                $.ajax({
                    type: 'post',
                    url: "{{ url('/') }}/get-refered-users",
                    data: { placementId }, // here $(this) refers to the ajax object not form
                    dataType: 'JSON',
                    success: function (res) {
                        parent.append(res.html);
                    }
                });
            }
            else if($(this).children('i').hasClass("fa-caret-down"))
            {
                $(this).parent("li").find('ul.list-arrow').html('');
                $(this).children('i').addClass("fa-caret-right");
                $(this).children('i').removeClass("fa-caret-down");
            }
        });
    });
</script>
@endsection