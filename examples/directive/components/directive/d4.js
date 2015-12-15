define(['app'], function(app) {
  app.registerDirective('hrmD4', [
    function() {
      return {
        restrict: 'EA',
        transclude: true,
        scope: {},
        templateUrl: 'components/view/d4.html',
        link: function ($scope, element) {
          $scope.name = 'Jeff';
          $scope.hello = 'hello';
        }
      };
    }
  ]);
});
