app.component('serialNumberSegmentList', {
    templateUrl: serial_number_segment_list_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope, $location) {
        $scope.loading = true;
        var self = this;
        self.hasPermission = HelperService.hasPermission;
        var dataTable = $('#serial_number_segment').DataTable({
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
            ajax: {
                url: laravel_routes['getSerialNumberSegmentList'],
                type: "GET",
                dataType: "json",
                data: function(d) {},
            },

            columns: [

                { data: 'action', class: 'action', name: 'action', searchable: false },
                { data: 'name', name: 'name' },
                { data: 'type', name: 'name' },
                { data: 'status', searchable: false },
            ],
            "infoCallback": function(settings, start, end, max, total, pre) {
                $('#table_info').html('(' + max + ')')
            },
            rowCallback: function(row, data) {
                $(row).addClass('highlight-row');
            }
        });
        $('.dataTables_length select').select2();
        $('#search_serial_number_segment').val(this.value);

        $scope.clear_search = function() {
            $('#search_serial_number_type').val('');
            $('#serial_number_type').DataTable().search('').draw();
        }

        var dataTables = $('#serial_number_segment').dataTable();
        $("#search_serial_number_segment").keyup(function() {
            dataTables.fnFilter(this.value);
        });

        $scope.deleteSerialNumberType = function($id) {
            $('#serial_number_segment_id').val($id);
        }
        $scope.deleteConfirm = function() {
            $id = $('#serial_number_segment_id').val();
            $http.get(
                serial_number_segment_delete_data_url + '/' + $id,
            ).then(function(response) {
                if (response.data.success) {
                    new Noty({
                        type: 'success',
                        layout: 'topRight',
                        text: 'Serial Number Type Deleted Successfully',
                    }).show();
                    $('#serial_number_type').DataTable().ajax.reload(function(json) {});
                    $location.path('/serial-number-pkg/serial-number-type/list');
                }
            });
        }
        $rootScope.loading = false;
    }
});
//------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------
app.component('serialNumberSegmentForm', {
    templateUrl: serial_number_segment_form_template_url,
    controller: function($http, $location, HelperService, $scope, $routeParams, $rootScope) {
        get_form_data_url = typeof($routeParams.id) == 'undefined' ? serial_number_segment_get_form_data_url : serial_number_segment_get_form_data_url + '/' + $routeParams.id;
        var self = this;
        self.hasPermission = HelperService.hasPermission;
        self.angular_routes = angular_routes;
        $http.get(
            get_form_data_url
        ).then(function(response) {
            console.log(response);
            self.serial_number_segment = response.data.serial_number_segment;
            self.type_list = response.data.type_list;
            self.action = response.data.action;
            if (response.data.action == 'Edit') {
                if (response.data.serial_number_segment.deleted_at) {
                    segment.switch_value = 'Inactive';
                } else {
                    segment.switch_value = 'Active';
                }
            }
            $scope.add_segment();
            $rootScope.loading = false;
        });
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
                $('#petty_cash_removal_id').val(JSON.stringify(self.segment_removal_id));
            }
            self.serial_number_segment.splice(index, 1);
        }

        var form_id = '#form';
        var v = jQuery(form_id).validate({
            ignore: '',
            rules: {
                'name': {
                    required: true,
                },
            },
            submitHandler: function(form) {
                let formData = new FormData($(form_id)[0]);
                $('#submit').button('loading');
                $.ajax({
                        url: laravel_routes['saveSerialNumberType'],
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
                            $location.path('/serial-number-pkg/serial-number-type/list');
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
                                $location.path('/serial-number-pkg/serial-number-type/list');
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