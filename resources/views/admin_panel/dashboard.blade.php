@extends('layouts.ap_app')

@section('app-name')
    Dashboard
@endsection

@section('main-content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="clearfix">
                    <div class="float-left">
                        <i class="mdi mdi-cube text-danger icon-lg"></i>
                    </div>
                    <div class="float-right">
                        <p class="mb-0 text-right">Total Revenue</p>
                        <div class="fluid-container">
                        <h3 class="font-weight-medium text-right mb-0">${{ $balance }}</h3>
                        </div>
                    </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> 65% lower growth
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                <div class="float-left">
                    <i class="mdi mdi-receipt text-warning icon-lg"></i>
                </div>
                <div class="float-right">
                    <p class="mb-0 text-right">Referal Users</p>
                    <div class="fluid-container">
                    <h3 class="font-weight-medium text-right mb-0">{{ $totalReferal }}</h3>
                    </div>
                </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> Product-wise sales
                </p>
            </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                <div class="float-left">
                    <i class="mdi mdi-poll-box text-success icon-lg"></i>
                </div>
                <div class="float-right">
                    <p class="mb-0 text-right">Golden Board Rank</p>
                    <div class="fluid-container">
                    <h3 class="font-weight-medium text-right mb-0">{{ isset($getGoldenBoardDtl->id)?$getGoldenBoardDtl->id:'N/A' }}</h3>
                    </div>
                </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Weekly Sales
                </p>
            </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
            <div class="card-body">
                <div class="clearfix">
                <div class="float-left">
                    <i class="mdi mdi-poll-box text-success icon-lg"></i>
                </div>
                <div class="float-right">
                    <p class="mb-0 text-right">Golden Board Rank</p>
                    <div class="fluid-container">
                    <h3 class="font-weight-medium text-right mb-0">{{ isset($getGoldenBoardDtl->id)?$getGoldenBoardDtl->id:'N/A' }}</h3>
                    </div>
                </div>
                </div>
                <p class="text-muted mt-3 mb-0">
                <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i> Weekly Sales
                </p>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
            <div class="card-body">
                <div class="row d-none d-sm-flex mb-4">
                <div class="col-4">
                    <h5 class="text-primary">Unique Visitors</h5>
                    <p>34657</p>
                </div>
                <div class="col-4">
                    <h5 class="text-primary">Bounce Rate</h5>
                    <p>45673</p>
                </div>
                <div class="col-4">
                    <h5 class="text-primary">Active session</h5>
                    <p>45673</p>
                </div>
                </div>
                <div class="chart-container">
                <canvas id="dashboard-area-chart" height="80"></canvas>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 grid-margin">
            <div class="card">
            <div class="card-body">
                <h4 class="card-title"><img src="{{ URL::to('/') }}<?= '/images/dollar-coin.png'; ?>" width="25px" alt="badge">Top 5 Golden Board Members</h4>
                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Profile Image</th>
                        <th>Full name</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($getTofiveGoldenBoardMembers as $key => $gbmem)
                    <tr>
                        <td class="font-weight-medium">{{ ++$key }}</td>
                        <td>
                            <div class="profile-image">
                                <?php
                                    $proimgSrc = '/images/profile_img/default_profile_img.png';

                                    if($gbmem->profile_img != "") $proimgSrc = '/uploads/profile_images/'.$gbmem->profile_img;                                
                                ?>
                                <img src="{{ URL::to('/') }}<?= $proimgSrc; ?>" alt="profile image">
                            </div>
                        </td>
                        <td>{{ $gbmem->first_name}}&nbsp;{{ $gbmem->last_name}}</td>
                    </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-6 grid-margin">
            <div class="card">
            <div class="card-body">
                <h4 class="card-title"><img src="{{ URL::to('/') }}<?= '/images/diamond.png'; ?>" width="25px" alt="badge">Top 5 Diamond Board Members</h4>
                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Profile Image</th>
                        <th>Full name</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($getTofiveDiamondBoardMembers as $key => $dbmem)
                    <tr>
                        <td class="font-weight-medium">{{ ++$key }}</td>
                        <td>
                            <div class="profile-image">
                                <?php
                                    $proimgSrc = '/images/profile_img/default_profile_img.png';

                                    if($dbmem->profile_img != "") $proimgSrc = '/uploads/profile_images/'.$dbmem->profile_img;                                
                                ?>
                                <img src="{{ URL::to('/') }}<?= $proimgSrc; ?>" alt="profile image">
                            </div>
                        </td>
                        <td>{{ $dbmem->first_name}}&nbsp;{{ $dbmem->last_name}}</td>
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
