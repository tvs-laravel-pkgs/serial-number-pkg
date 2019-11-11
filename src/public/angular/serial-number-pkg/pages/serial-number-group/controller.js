app.component('serialNumberGroupList', {
    templateUrl: serial_number_group_list_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope, $location) {
        $scope.loading = true;
        var self = this;
        self.hasPermission = HelperService.hasPermission;
        var table_scroll;
        table_scroll = $('.page-main-content').height() - 37;
        var dataTable = $('#serial_number_group').DataTable({
            "dom": cndn_dom_structure,
            "language": {
                "search": "",
                "searchPlaceholder": "Search",
                "lengthMenu": "Rows _MENU_",
                "paginate": {
                    "next": '<i class="icon ion-ios-arrow-forward"></i>',
                    "previous": '<i class="icon ion-ios-arrow-back"></i>'
                },
            },
            pageLength: 10,
            processing: true,
            serverSide: true,
            paging: true,
            stateSave: true,
            ordering: false,
            scrollY: table_scroll + "px",
            scrollCollapse: true,
            ajax: {
                url: laravel_routes['getSerialNumberGroupList'],
                type: "GET",
                dataType: "json",
                data: function(d) {},
            },

            columns: [

                { data: 'action', class: 'action', name: 'action', searchable: false },
                { data: 'type', name: 'configs.name' },
                { data: 'financialyear', name: 'financialyear' },
                { data: 'state', name: 'state' },
                { data: 'branch', name: 'branch' },
                { data: 'start_no', name: 'starting_number' },
                { data: 'end_no', name: 'ending_number' },
                { data: 'next_no', name: 'next_number' },
                { data: 'segment', name: 'segment' },
                // { data: 'status', searchable: false },
            ],
            "infoCallback": function(settings, start, end, max, total, pre) {
                $('#table_info').html('(' + max + ')')
            },
            rowCallback: function(row, data) {
                $(row).addClass('highlight-row');
            }
        });
        $('.dataTables_length select').select2();
        $('#search_serial_number_group').val(this.value);

        $scope.clear_search = function() {
            $('#search_serial_number_group').val('');
            $('#serial_number_group').DataTable().search('').draw();
        }

        var dataTables = $('#serial_number_group').dataTable();
        $("#search_serial_number_group").keyup(function() {
            dataTables.fnFilter(this.value);
        });

        $scope.deleteSerialNumberType = function($id) {
            $('#serial_number_group_id').val($id);
        }
        $scope.deleteConfirm = function() {
            $id = $('#serial_number_group_id').val();
            $http.get(
                serial_number_group_delete_data_url + '/' + $id,
            ).then(function(response) {
                if (response.data.success) {
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: 'Serial Number Group Deleted Successfully',
                    }).show();
                    $('#serial_number_group').DataTable().ajax.reload(function(json) {});
                    $location.path('/serial-number-pkg/serial-number-group/list');
                }
            });
        }
        $rootScope.loading = false;
    }
});
//------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------
app.component('serialNumberGroupForm', {
    templateUrl: serial_number_group_form_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope) {
        get_form_data_url = typeof($routeParams.id) == 'undefined' ? serial_number_group_get_form_data_url : serial_number_group_get_form_data_url + '/' + $routeParams.id;
        var self = this;
        self.hasPermission = HelperService.hasPermission;
        self.angular_routes = angular_routes;
        $http.get(
            get_form_data_url
        ).then(function(response) {
            console.log(response);
            self.serial_number_group = response.data.serial_number_group;
            self.type_list = response.data.type_list;
            self.state_list = response.data.state_list;
            self.action = response.data.action;
            if (response.data.action == 'Edit') {

            } else {
                self.switch_value = 'Active';
            }
            $rootScope.loading = false;
        });

        //SHOW BRANCH BASED STATE
        $scope.onSelectedState = function($id) {
            alert($id);
            $http.get(
                get_branch_based_state_url + '/' + $id
            ).then(function(response) {

            });
        }
        //ADD SEGMENT
        $scope.add_segment = function() {
            self.serial_number_segment.push({
                switch_value: 'Active',
            });
        }
        //REMOVE SEGMENT 
        $scope.removeSegment = function(index, segment_id) {
            console.log(index, segment_id);
            if (segment_id) {
                self.segment_removal_id.push(segment_id);
                $('#segment_removal_id').val(JSON.stringify(self.segment_removal_id));
            }
            self.serial_number_segment.splice(index, 1);
        }

        var form_id = '#form';
        var v = jQuery(form_id).validate({
            ignore: '',
            submitHandler: function(form) {
                let formData = new FormData($(form_id)[0]);
                $('#submit').button('loading');
                $.ajax({
                        url: laravel_routes['saveSerialNumberGroup'],
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                    })
                    .done(function(res) {
                        if (res.success == true) {
                            new Noty({
                                type: 'success',
                                layout: 'topRight',
                                text: res.message,
                            }).show();
                            $location.path('/serial-number-pkg/serial-number-group/list');
                            $scope.$apply();
                        } else {
                            if (!res.success == true) {
                                $('#submit').button('reset');
                                var errors = '';
                                for (var i in res.errors) {
                                    errors += '<li>' + res.errors[i] + '</li>';
                                }
                                new Noty({
                                    type: 'error',
                                    layout: 'topRight',
                                    text: errors
                                }).show();
                            } else {
                                $('#submit').button('reset');
                                $location.path('/serial-number-pkg/serial-number-group/list');
                                $scope.$apply();
                            }
                        }
                    })
                    .fail(function(xhr) {
                        $('#submit').button('reset');
                        new Noty({
                            type: 'error',
                            layout: 'topRight',
                            text: 'Something went wrong at server',
                        }).show();
                    });
            }
        });
    }
});