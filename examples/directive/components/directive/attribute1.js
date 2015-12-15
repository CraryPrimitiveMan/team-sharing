define(['app'], function(app) {
  app.registerDirective('hrmAttribure1', [
    function() {
      return {
        restrict: 'EA',
        scope: true,
        replace: true,
        template: function(tElement,tAttrs){
          console.log(tAttrs);
          var _html = '';
          _html += '<div>{{who}}</div>';
          return _html;
        },
        controller: function($scope, $element){
          console.log(' controller');
          $scope.who = $scope.who + "controller ";
        },
        controllerAs:'myController',
        link: function(scope, el, attr) {
          scope.who = scope.who + "link ";
        },
        compile: function(element, attributes) {
          console.log(' compile');
          return {
            pre: function preLink(scope, element, attributes) {
              scope.who = scope.who + "compile pre ";
              console.log(' pre');
            },
            post: function postLink(scope, element, attributes) {
              scope.who = scope.who + "compile link ";
              console.log(' link');
            }
          };
        }
      };
    }
  ]);
});
