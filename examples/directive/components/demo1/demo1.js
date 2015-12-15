define(['app', 'components/directive/d1', 'components/directive/d2'], function(app) {
    'use strict';

    app.registerController('hrm.demo1', demo1Controller);

    demo1Controller.$inject = ['$scope'];

    function demo1Controller($scope) {
        var gary = this;
        gary.title = 'Demo 1';
        gary.check = true;
        return gary;
    }
});
