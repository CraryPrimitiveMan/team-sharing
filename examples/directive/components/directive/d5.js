define(['app'], function(app) {
  app.registerDirective('myTabs', [
    function() {
      return {
        restrict: 'EA',
        transclude: true,
        scope: {},
        controller: function($scope) {
          var panes = $scope.panes = [];

          $scope.select = function(pane) {
            angular.forEach(panes, function(pane) {
              pane.selected = false;
            });
            pane.selected = true;
          };

          this.addPane = function(pane) {
            if (panes.length === 0) {
              $scope.select(pane);
            }
            panes.push(pane);
          };
        },
        controllerAs: 'tabController',
        templateUrl: 'components/view/tab.html'
      };
    }
  ]);
  app.registerDirective('myPane', [
    function() {
      return {
        require: '^tabController',
        restrict: 'EA',
        transclude: true,
        scope: {
          title: '@'
        },
        link: function(scope, element, attrs, tabsCtrl) {
          tabsCtrl.addPane(scope);
        },
        templateUrl: 'components/view/pane.html'
      };
    }
  ]);
});
