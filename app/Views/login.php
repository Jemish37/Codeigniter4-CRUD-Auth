<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="js/angular.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/comman.js"></script>



</head>

<body class="bg-gradient-primary" ng-app="myApp" ng-controller="myController">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." ng-model="email">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" ng-model="password">
                                        </div>

                                        <button ng-click="submit()" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                        <hr>

                                    </form>

                                    <div class="text-center">
                                        <a class="small" href="register">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        
        var app = angular.module('myApp', []);
        app.controller('myController', function($scope, $http) {
            $scope.email = "";
            $scope.password = "";
            $http.defaults.headers.common.Authorization = 'Basic Authorization';

            $scope.submit = () => {
                event.preventDefault();

                if ($scope.email == "" || $scope.email == undefined || $scope.email == null) {
                    toast("Email address must be provided");
                    return false;
                }

                if (!chk_email($scope.email)) {
                    toast("Email must be a valid email address");
                    return false;
                }

                if ($scope.password == "" || $scope.password == undefined || $scope.password == null) {
                    toast("Password must be provided");
                    return false;
                }

                if ($scope.password.length < 8) {
                    toast("Invalid Password");
                    return false;
                }

                senddata = $.param({
                    email: $scope.email,
                    password: $scope.password,
                })

                let url = "<?= base_url() ?>/api/login";
                $http({
                    method: 'POST',
                    url: url,
                    data: senddata,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function(response) {
                    let res = response.data;
                    if (res.status) {
                        notify(res.msg, "success", "<?= base_url() ?>");
                        localStorage.setItem("token", res.data.token);
                    } else {
                        toast(res.msg);

                    }
                }, function(response) {
                    toast("Something went wrong");
                });

            }

        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>