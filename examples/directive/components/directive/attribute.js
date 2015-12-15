define(['app'], function(app) {
  app.registerDirective('hrmAttribure', [
    function() {
      return {
        restrict: 'EA',
        priority: 4,
        scope: true,
        replace: true,
        template: '<div>{{who}}!</div>',
        controller: function($scope, $element){
          $scope.who = $scope.who + "controller ";
        },
        controllerAs:'myController',
        link: function(scope, el, attr) {
          scope.who = scope.who + "link ";
        },
        compile: function(element, attributes) {
          return {
            pre: function preLink(scope, element, attributes) {
              scope.who = scope.who + "compile pre ";
            },
            post: function postLink(scope, element, attributes) {
              scope.who = scope.who + "compile link ";
            }
          };
        }
      };
    }
  ]);
});
