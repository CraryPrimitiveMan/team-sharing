define(['app'], function(app) {
  app.registerDirective('hrmD2', [
    function() {
      return {
        priority: 1,
        scope: {
          'model': '=ngModel'
        },
        terminal: true,
        restrict: 'EA',
        transclude: true,
        templateUrl: 'components/view/t.html'
      };
    }
  ]);
});
