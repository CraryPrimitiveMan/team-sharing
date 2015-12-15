define(['app', 'components/directive/d3', 'components/directive/d4', 'components/directive/d5'], function(app) {
    'use strict';

    app.registerController('hrm.demo3', demo3Controller);

    demo3Controller.$inject = ['$scope', '$timeout'];

    function demo3Controller($scope, $timeout) {
        var hm = this;
        hm.title = 'Demo 3';
        $scope.name = 'Tobias';
        $scope.hideDialog = function () {
            $scope.dialogIsHidden = true;
            $timeout(function () {
                $scope.dialogIsHidden = false;
            }, 2000);
        };

        return hm;

    }
});
