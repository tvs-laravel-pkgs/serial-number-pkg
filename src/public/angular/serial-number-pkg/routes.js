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
    when('/serial-number-pkg/serial-number-code/edit/:id', {
        template: '<serial-number-code-form></serial-number-code-form>',
        title: 'Edit Serial Number Type',
    })

    //serial-number
    when('/serial-number-pkg/serial-number-segment/list', {
        template: '<serial-number-segment-list></serial-number-segment-list>',
        title: 'Serial Number Segments',
    }).
    when('/serial-number-pkg/serial-number-segment/add', {
        template: '<serial-number-segment-form></serial-number-segment-form>',
        title: 'Add Serial Number Segment',
    }).
    when('/serial-number-pkg/serial-number/edit/:id', {
        template: '<serial-number-segment-form></serial-number-segment-form>',
        title: 'Edit Serial Number Segment',
    });
}]);