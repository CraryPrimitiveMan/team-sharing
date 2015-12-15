define(['app'], function(app) {
  app.registerDirective('hrmD3', [
    function() {
      return {
        restrict: 'EA',
        transclude: true,
        scope: {
          'close': '&onClose'
        },
        templateUrl: 'components/view/d3.html'
      };
    }
  ]);
});
