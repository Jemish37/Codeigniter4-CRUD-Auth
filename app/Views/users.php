<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="vendor/datatables/buttons.dataTables.min.css">


<!-- Begin Page Content -->
<style>
    .modal .row .col-sm {
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .dataTables_length label {
        margin: 5px 0px auto 10px !important
    }
</style>
<div class="modal fade bd-example-modal-md" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm">
                            Name :
                        </div>
                        <div class="col-sm">
                            {{user.name}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            Email :
                        </div>
                        <div class="col-sm">
                            {{user.email}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            Contact No. :
                        </div>
                        <div class="col-sm">
                            {{user.contact_number}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            Post Code :
                        </div>
                        <div class="col-sm">
                            {{user.post_code}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            Gender :
                        </div>
                        <div class="col-sm">
                            {{user.gender}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            Hobbies :
                        </div>
                        <div class="col-sm">
                            {{user.hobbies}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            City :
                        </div>
                        <div class="col-sm">
                            {{user.city}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            Created At :
                        </div>
                        <div class="col-sm">
                            {{user.created_at}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            Updated At :
                        </div>
                        <div class="col-sm">
                            {{user.updated_at}}
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-12 my-4 " ng-show="user.images.length > 0">
                    <span class="h5">User's Images</span>
                    <div class="row">
                        <div class="col-md-4 my-2" ng-show="user.images != []" ng-repeat="src in user.images">
                            <div class="card">
                                <img class="card-img-top" onerror="this.src='uploads/404.png'" ng-src="<?= base_url() ?>/uploads/{{src}}" alt="Card image cap">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!-- <h6 class="m-0 font-weight-bold text-primary">Users</h6> -->
            <form class="form d-flex">
                <div class="row">
                    <div class="form-group mb-2 ml-2 col-auto">
                        <label class="mr-1">Select State</label>
                        <select class="form-control default-select" id="selState" ng-model="selstate">
                            <option value="" select>All</option>
                            <option value="{{item.state}}" ng-repeat="item in states">{{item.state}}</option>
                        </select>
                    </div>
                    <div class="form-group mb-2 ml-2 col-auto">
                        <label class="mr-1">Select City</label>
                        <select class="form-control default-select" id="selCity" ng-model="selcity">
                            <option value="" select>All</option>
                            <option value="{{item.id}}" ng-repeat="item in cities | filter:{ state: selstate }">{{item.city}}</option>
                        </select>
                    </div>
                    <div class="form-group mb-2 ml-2 col-auto">
                        <label class="mr-1">Select Gender</label>
                        <select class="form-control default-select" id="selGender" ng-model="selGender">
                            <option value="" select>All</option>
                            <option value="0">Male</option>
                            <option value="1">Female</option>
                            <option value="2">Rather not Say</option>

                        </select>
                    </div>

                    <div class="mt-auto mb-0 col-auto">
                        <label for="">&nbsp;</label>
                        <button type="submit" class="btn btn-primary mb-2 ml-2" ng-disabled="searchload" ng-click="getlist()">Search<i class="ml-2 fa fa-spin fa-spinner" ng-show="searchload"></i></button>
                    </div>
                </div>

            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datatable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th class="text-right">Contact No.</th>
                            <th>Gender</th>
                            <th>City</th>
                            <th>Post Code</th>
                            <th>Hobbies</th>
                            <th>Created Date</th>
                            <th>Updated Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->


<script>
    var app = angular.module('myApp', []);
    app.controller('myController', function($scope, $http, $timeout, $compile) {

        let token = localStorage.getItem("token");
        if (!token) {
            location.href = "<?= base_url() ?>/login";
        }
        $http.defaults.headers.common.Authorization = 'Bearer ' + token;

        $scope.cities = <?= json_encode($cities) ?>;
        $scope.states = <?= json_encode($states) ?>;

        $scope.selCity = "";
        $scope.selState = "";

        var columnToExport = [0, 1, 2, 3, 4, 5, 6,7,8,9];
        $scope.getlist = function() {
            $scope.searchload = true;
            if ($.fn.DataTable.isDataTable('#datatable')) {
                $('#datatable').DataTable().destroy();
            }
            $scope.gettable = $('#datatable').DataTable({

                "scrollX": true,
                "processing": true,
                "pageLength": 10,
                "select": true,
                "serverSide": true,
                "autoWidth": false,
                "ajax": {
                    "type": "POST",
                    "url": "<?= base_url() ?>/api/all-users",
                    headers: {
                        "Authorization": 'Bearer ' + token
                    },
                    "data": function(d) {

                        d.selState = $("#selState").val();
                        d.selCity = $("#selCity").val();
                        d.selGender = $("#selGender").val();
                    },
                    "dataSrc": function(json) {
                        $timeout(function() {
                            $scope.searchload = false;
                            $($.fn.dataTable.tables(true)).DataTable()
                                .columns.adjust();
                        }, 200);

                        // $('#datatable_filter input').addClass('d-none');
                        $scope.userdata = json.data.data;
                        json.recordsTotal = json.data.recordsTotal;
                        json.recordsFiltered = json.data.recordsFiltered;
                        return json.data.data;
                    }
                },
                "paging": true,
                "order": [
                    [0, "desc"]
                ],

                "columns": [{
                        "data": "cnt",
                        "defaultContent": null,
                        "className": 'text-nowrap',
                    },
                    {
                        "data": "email",
                        "defaultContent": null,
                        "className": 'text-nowrap',
                    },
                    {
                        "data": "name",
                        "defaultContent": null,
                        "className": 'text-nowrap',

                    },
                    {
                        "data": "contact_number",
                        "defaultContent": null,
                        "className": 'text-nowrap text-right',
                    },
                    {
                        "data": "gender",
                        "defaultContent": null,
                        "className": 'text-nowrap',
                    },
                    {
                        "data": "city",
                        "defaultContent": null,
                        "className": 'text-nowrap',
                    },
                    {
                        "data": "post_code",
                        "defaultContent": null,
                        "className": 'text-nowrap',
                        visible: false

                    },
                    {
                        "data": "hobbies",
                        "defaultContent": null,
                        "className": 'text-nowrap',
                        visible: false
                    },
                    {
                        "data": "created_at",
                        "defaultContent": null,
                        "className": 'text-nowrap',
                    },
                    {
                        "data": "updated_at",
                        "defaultContent": null,
                        "className": 'text-nowrap',
                        visible: false

                    },
                    {
                        "data": "id",
                        "defaultContent": null,
                        "className": 'text-center',
                        render: (data, type, row, meta) => {
                            let viewele = `<button type="button" ng-click="setUser(${meta.row})" class="btn btn-sm btn-info" title="More Details" data-toggle="modal" data-target="#exampleModal"> <i class="fa fa-info-circle"></i> </button>`;
                            let delele = `<button type="button" ng-click="delUser('${row.id}')" class="btn btn-sm btn-danger" title="Delete User"> <i class="fa fa-trash"></i> </button>`;

                            let ele = viewele + " " + delele;
                            return ele;
                        }
                    },

                ],
                createdRow: function(row, data, dataIndex) {
                    $compile(angular.element(row).contents())($scope);
                },

                "lengthChange": true,
                "deferRender": true,
                "language": {
                    "emptyTable": "<img width='200' src='<?= base_url() ?>/uploads/404.png'>",
                    search: '',
                    searchPlaceholder: "Search..."

                },
                dom: 'Blfrtip',
                buttons: [{
                        extend: 'pdf',
                        exportOptions: {
                            columns: columnToExport // indexes of the columns that should be printed,
                        } // Exclude indexes that you don't want to print.
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: columnToExport
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: columnToExport
                        }
                    }
                ],

            });
        };
        $scope.getlist();

        $scope.setUser = (index) => {
            $scope.user = $scope.userdata[index];
            $scope.user.images = $scope.user.images != null ? $scope.user.images.split(",") : [];
        }

        $scope.delUser = (id) => {

            senddata = $.param({
                id
            })

            Swal.fire({
                title: 'Do you want to delete this user?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    let url = "<?= base_url() ?>/api/delete-user";
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
                            notify(res.msg, "success");
                            $scope.getlist();

                        } else {
                            toast(res.msg);

                        }
                    }, function(response) {
                        toast("Something went wrong");
                    });
                }
            })
        }
    });
</script>

<!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>


<script src="vendor/datatables/dataTables.buttons.min.js"> </script>
<script src="vendor/datatables/jszip.min.js"> </script>
<script src="vendor/datatables/pdfmake.min.js"> </script>
<script src="vendor/datatables/vfs_fonts.js"> </script>
<script src="vendor/datatables/buttons.html5.min.js"> </script>
<script src="vendor/datatables/buttons.print.min.js"> </script>