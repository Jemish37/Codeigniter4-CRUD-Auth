<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title ?></title>

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

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="First Name" ng-model="firstName">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName" placeholder="Last Name" ng-model="lastName">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" ng-model="email">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" ng-model="password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Confirm Password" ng-model="confirmPassword">
                                    </div>
                                </div>
                                <button ng-click="submit()" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                                <hr>
                            </form>

                            <div class="text-center">
                                <a class="small" href="login">Already have an account? Login!</a>
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
            $scope.firstName = "";
            $scope.lastName = "";
            $scope.email = "";
            $scope.password = "";
            $scope.confirmPassword = "";

            $http.defaults.headers.common.Authorization = 'Basic Authorization';

            $scope.submit = () => {
                event.preventDefault();
                if ($scope.firstName == "" || $scope.firstName == undefined || $scope.firstName == null) {
                    toast("First name must be provided");
                    return false;
                }

                if (!chk_string($scope.firstName)) {
                    toast("First name must be String");
                    return false;
                }

                if ($scope.lastName == "" || $scope.lastName == undefined || $scope.lastName == null) {
                    toast("Last name must be provided");
                    return false;
                }

                if (!chk_string($scope.lastName)) {
                    toast("Last name must be String");
                    return false;
                }

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

                if (!chk_password($scope.password)) {
                    toast("Password must be combination of Upper and Lower case letters, digits and special characters");
                    return false;
                }

                if ($scope.confirmPassword == "" || $scope.confirmPassword == undefined || $scope.confirmPassword == null) {
                    toast("Confirm password must be provided");
                    return false;
                }

                if ($scope.password != $scope.confirmPassword) {
                    toast("Password and Confirm Password must be the same");
                }


                senddata = $.param({
                    email: $scope.email,
                    first_name: $scope.firstName,
                    last_name: $scope.lastName,
                    password: $scope.password,
                })

                let url = "<?= base_url() ?>/api/register";
                $http({
                    method: 'POST',
                    url: url,
                    data: senddata,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function(response) {
                    let res = response.data;
                    if (res.status){
                        notify(res.msg,"success","<?=base_url()?>/login");
                    }else{
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