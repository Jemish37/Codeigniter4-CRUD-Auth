<style>
    .gallery img {
        width: 100px !important;
        margin: 10px !important;
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Your Profile</h1>
    </div>
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Personal Details</h5>
                    <form class="row" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email address</label>
                                <input type="text" ng-model="user.email" class="form-control" placeholder="Enter email" autocomplete="new-password" readonly>
                                <small id="emailHelp" class="form-text text-muted">You can't change Email Address</small>
                            </div>

                            <div class="form-group">
                                <label> First Name </label>
                                <input type="text" ng-model="user.first_name" class="form-control" placeholder="Enter First Name">
                            </div>

                            <div class="form-group">
                                <label> Last Name </label>
                                <input type="text" ng-model="user.last_name" class="form-control" placeholder="Enter Last Name">
                            </div>

                            <div class="form-group">
                                <label> Contact Number </label>
                                <input type="text" ng-model="user.contact_number" class="form-control" placeholder="Enter Contact Number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">New Password</label>
                                <input type="password" ng-model="user.password" class="form-control" id="exampleInputPassword1" placeholder="Password" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label> Gender </label>
                                <div class="d-flex justify-content-around">
                                    <div class="form-check">
                                        <input class="form-check-input" ng-model="user.gender" type="radio" name="exampleRadios" id="exampleRadios1" value="0">
                                        <label class="form-check-label" for="exampleRadios1">
                                            Male
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" ng-model="user.gender" type="radio" name="exampleRadios" id="exampleRadios2" value="1">
                                        <label class="form-check-label" for="exampleRadios2">
                                            Female
                                        </label>
                                    </div>
                                    <div class="form-check disabled">
                                        <input class="form-check-input" ng-model="user.gender" type="radio" name="exampleRadios" id="exampleRadios3" value="2">
                                        <label class="form-check-label" for="exampleRadios3">
                                            Rather not Say
                                        </label>
                                    </div>
                                </div>
                                <hr class="sidebar-divider my-3">
                            </div>
                            <div class="form-group">
                                <label> Hobbies </label>
                                <div class="d-flex justify-content-around">

                                    <div class="form-check" ng-repeat="value in hobbies">
                                        <input class="form-check-input" ng-model="selectedHobbies[value]" id="{{value}}" type="checkbox" value="{{value}}">
                                        <label class="form-check-label" for="{{value}}">
                                            {{value}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr class="sidebar-divider my-3">
                            <div class="form-group">
                                <label> City </label>
                                <select class="custom-select custom-select mb-3" ng-model="user.city">
                                    <option value="">Select City</option>
                                    <option value="{{x.id}}" ng-repeat="x in cities">{{x.city}}, {{x.state}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label> Post Code </label>
                                <input type="text" ng-model="user.post_code" class="form-control" placeholder="Enter Post Code" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>
                            <div class="form-group">
                                <label> Upload Your Photos </label>
                                <div class="custom-file">
                                    <input type="file" ng-file='images' class="custom-file-input" ng-model="images" id="images" name="images" multiple accept="image/*">
                                    <label class="custom-file-label" for="images">Choose file</label>
                                </div>
                            </div>
                            <div class="gallery"></div>
                            <button ng-click="submit()" class="btn btn-primary float-right">Save</button>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-xl-12 col-md-12 my-4" ng-show="user.images.length > 0">
            <h3>Your Images </h3>
            <div class="row">
                <div class="col-md-4 my-4" ng-show="user.images != []" ng-repeat="src in user.images">
                    <div class="card">
                        <img class="card-img-top" onerror="this.src='uploads/404.png'" ng-src="<?= base_url() ?>/uploads/{{src}}" alt="Card image cap">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->


<script>
    var imagesPreview = function(input, placeToInsertImagePreview) {
        if (input.files) {
            var filesAmount = input.files.length;
            $(placeToInsertImagePreview).empty();

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    function validateImage() {
        var uploadImg = document.getElementById('images');
        //uploadImg.files: FileList
        for (var i = 0; i < uploadImg.files.length; i++) {
            var f = uploadImg.files[i];
            if (!endsWith(f.name, 'jpg') && !endsWith(f.name, 'png') && !endsWith(f.name, 'gif')) {
                notify(f.name + " is not a valid image file!", "error");
                return false;
            } else if (f.size > 3000000) {
                notify(f.name + " is too large, Max Upload Size is 3 MB", "error");
                return false;
            } else {
                return true;
            }
        }
    }

    function endsWith(str, suffix) {
        return str.indexOf(suffix, str.length - suffix.length) !== -1;
    }

    $('#images').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });

    var app = angular.module('myApp', []);
    app.directive('ngFile', ['$parse', function($parse) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                element.bind('change', function() {
                    $parse(attrs.ngFile).assign(scope, element[0].files)
                    scope.$apply();
                });
            }
        };
    }]);
    app.controller('myController', function($scope, $http) {

        let token = localStorage.getItem("token");
        $http.defaults.headers.common.Authorization = 'Bearer ' + token;
        $scope.cities = <?= json_encode($cities) ?>;
        // console.log($scope.cities);

        $scope.hobbies = [
            "Gaming",
            "Movies",
            "Music",
            "Exploring",
            "Research"
        ]

        $scope.selectedHobbies = {};

        $scope.getData = () => {
            let url = "<?= base_url() ?>/api/user-data";
            $http({
                method: 'GET',
                url: url,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }

            }).then(function(response) {
                let res = response.data;
                if (res.status) {
                    $scope.user = res.data;
                    $scope.user.password = "";
                    if (res.data.hobbies != "" && res.data.hobbies != null) {
                        var hobbies = [];
                        if (res.data.hobbies.includes(",")) {

                            hobbies = res.data.hobbies.split(",");
                        } else {
                            hobbies = [res.data.hobbies];
                        }

                        hobbies.forEach(element => {
                            $scope.selectedHobbies[element] = true;
                        });

                        if (res.data.images != null && res.data.images.includes(",")) {
                            $scope.user.images = res.data.images.split(",");
                        } else {
                            $scope.user.images = [];

                        }


                    }
                    // console.log($scope.user);
                } else {
                    toast(res.msg);
                }
            }, function(response) {
                toast("Session Expired");
            });
        }

        $scope.getData();

        $scope.submit = () => {


            $scope.user.hobbies = "";
            let hobbieArray = [];
            $scope.hobbies.forEach((element, key) => {
                if ($scope.selectedHobbies[element] && $scope.selectedHobbies[element] == true) {
                    hobbieArray.push(element);
                }
            });
            $scope.user.hobbies = hobbieArray.join(",");


            if ($scope.user.first_name == "" || $scope.user.first_name == undefined || $scope.user.first_name == null) {
                toast("First name must be provided");
                return false;
            }

            if (!chk_string($scope.user.first_name)) {
                toast("First name must be String");
                return false;
            }

            if ($scope.user.last_name == "" || $scope.user.last_name == undefined || $scope.user.last_name == null) {
                toast("Last name must be provided");
                return false;
            }

            if (!chk_string($scope.user.last_name)) {
                toast("Last name must be String");
                return false;
            }

            if ($scope.user.contact_number == "" || $scope.user.contact_number == null || $scope.user.contact_number == undefined) {
                toast("Contact number must be provided");
                return false;
            }

            if ($scope.user.contact_number.length != 10) {
                toast("Contact number must be of 10 Digits");
                return false;
            }

            if ($scope.user.password != "" && $scope.user.password != null && $scope.user.password != undefined) {
                if (!chk_password($scope.user.password)) {
                    toast("Password must be combination of Upper and Lower case letters, digits and special characters");
                    return false;
                }
            }

            if ($scope.user.gender == "" || $scope.user.gender == null || $scope.user.gender == undefined) {
                toast("Gender must be specified");
                return false;
            }

            if ($scope.user.hobbies == "" || $scope.user.hobbies == null || $scope.user.hobbies == undefined) {
                toast("Please select at least one from hobbies");
                return false;
            }

            if ($scope.user.city == "" || $scope.user.city == null || $scope.user.city == undefined) {
                toast("City must be provided");
                return false;
            }

            if ($scope.user.post_code == "" || $scope.user.post_code == null || $scope.user.post_code == undefined) {
                toast("Post Code must be provided");
                return false;
            }

            if ($scope.user.post_code.length != 6) {
                toast("Post Code must be of 6 Digits");
                return false;
            }

            validateImage();

            var form = new FormData();
            form.append("first_name", $scope.user.first_name);
            form.append("last_name", $scope.user.last_name);
            form.append("contact_number", $scope.user.contact_number);
            form.append("post_code", $scope.user.post_code);
            form.append("hobbies", $scope.user.hobbies);
            form.append("gender", $scope.user.gender.toString());
            form.append("city", $scope.user.city);
            form.append("password", $scope.user.password);

            angular.forEach($scope.images, function(file) {
                form.append('file[]', file);
            });
            let url = "<?= base_url() ?>/api/update-profile";

            $http({
                method: 'POST',
                url: url,
                data: form,
                headers: {
                    'Content-Type': undefined
                }

            }).then(function(response) {
                let res = response.data;
                if (res.status) {
                    notify(res.msg, "success");
                    $scope.getData();

                } else {
                    toast(res.msg);

                }
            }, function(response) {
                toast("Something went wrong");
            });

        }

    });
</script>