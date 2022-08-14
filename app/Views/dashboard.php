<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-6 col-md-6 mb-4">
            Welcome <span class="text-primary"> <?= $userdata['first_name'] . "" . " " . $userdata['last_name'] ?> </span>
            <br>
            You can update your profile in <a href="<?=base_url()?>/profile"> Profile </a> Section
        </div>

    </div>
    <!-- /.container-fluid -->


    

    <script>
        var app = angular.module('myApp', []);
        app.controller('myController', function($scope, $http) {
            

        });
    </script>