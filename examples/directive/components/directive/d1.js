define(['app'], function(app) {
  app.registerDirective('hrmD1', [
    function() {
      return {
        restrict: 'EA',
        scope: {
          'a': '=ngModel'
        },
        priority: 3,
        terminal: true,
        template: '<div>d1 {{a}}</div>'
      };
    }
  ]);
});
