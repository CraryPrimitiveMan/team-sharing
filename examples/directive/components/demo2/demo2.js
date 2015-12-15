define(['app', 'components/directive/attribute', 'components/directive/attribute1'], function(app) {
    'use strict';

    app.registerController('hrm.demo2', demo1Controller);

    demo1Controller.$inject = ['$scope'];

    function demo1Controller($scope) {
        $scope.who = 'demo 2 ';
        var hm = this;
        hm.title = 'Demo 2 attribute';
        hm.check = true;
        hm.show1 = false;


        hm.runCode = function(index) {
            hm['show' + index] = true;
        };

        hm.hideCode = function(index) {
            hm['show' + index] = false;
        };

        return hm;
    }
});
