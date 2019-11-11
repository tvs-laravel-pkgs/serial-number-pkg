app.config(['$routeProvider', function($routeProvider) {

    $routeProvider.
    //SERIAL NUMBER TYPES
    when('/serial-number-pkg/serial-number-type/list', {
        template: '<serial-number-type-list></serial-number-type-list>',
        title: 'Serial Number Types',
    }).
    when('/serial-number-pkg/serial-number-type/add', {
        template: '<serial-number-type-form></serial-number-type-form>',
        title: 'Add Serial Number Type',
    }).
    when('/serial-number-pkg/serial-number-type/edit/:id', {
        template: '<serial-number-type-form></serial-number-type-form>',
        title: 'Edit Serial Number Type',
    }).

    //SERIAL NUMBER SEGMENT
    when('/serial-number-pkg/serial-number-segment/list', {
        template: '<serial-number-segment-list></serial-number-segment-list>',
        title: 'Serial Number Segments',
    }).
    when('/serial-number-pkg/serial-number-segment/add', {
        template: '<serial-number-segment-form></serial-number-segment-form>',
        title: 'Add Serial Number Segment',
    }).
    when('/serial-number-pkg/serial-number-segment/edit/:id', {
        template: '<serial-number-segment-form></serial-number-segment-form>',
        title: 'Edit Serial Number Segment',
    }).

    //SERIAL NUMBER GROUP
    when('/serial-number-pkg/serial-number-group/list', {
        template: '<serial-number-group-list></serial-number-group-list>',
        title: 'Serial Number Groups',
    }).
    when('/serial-number-pkg/serial-number-group/add', {
        template: '<serial-number-group-form></serial-number-group-form>',
        title: 'Add Serial Number Group',
    }).
    when('/serial-number-pkg/serial-number-group/edit/:id', {
        template: '<serial-number-group-form></serial-number-group-form>',
        title: 'Edit Serial Number Group',
    });
}]);