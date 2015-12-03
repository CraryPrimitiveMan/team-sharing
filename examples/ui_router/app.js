angular.module('app', [
  'ui.router',
  'ngAnimate',
  /* ui.bootstrap depends on ngAnimate */
  'ui.bootstrap'
])
.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
  $stateProvider
    /*
      Allow you to extends or override the stateBuilder
      http://angular-ui.github.io/ui-router/site/#/api/ui.router.state.$stateProvider
     */
    .decorator('views', function (state, parent) {
      var result = {}, views = parent(state);

      angular.forEach(views, function (config, name) {
        // console.log(config);

        if (config.templateUrl) {
          config.templateUrl = 'app/' + config.templateUrl;
        }

        result[name] = config;
      });

      return result;
    })

    .state('home', {
      /* Default view */
      url: '/home',
      controller: ['$state', function ($state) {
        $state.go('home.homeChild');
      }],
      /* Data can be override */
      data: {
        'name': '！！！！！！',
        'gender': 'boy'
      },
      onEnter: ['$stateParams', '$state', '$uibModal', function($stateParams, $state, $uibModal) {
        $uibModal.open({
          templateUrl: 'app/homeModal.html',
          resolve: {
            items: function () {
              return ['item1', 'item2', 'item3'];
            }
          },
          controller: ['$scope', '$uibModalInstance', 'items', function ($scope, $uibModalInstance, items) {
            $scope.items = items;

            $scope.close = function () {
              $uibModalInstance.dismiss('close');
            };
          }]
        });
      }],
      onExit: function () {
        alert('call onExit function');
      }
    })

    .state('home.homeChild', {
      templateUrl: 'home-child.html',
      controller: ['$scope', '$state', function ($scope, $state) {
        $scope.homeData = $state.current.data;
      }]
    })

    .state('about', {
      url: '/about',
      /* A function to return html */
      templateProvider: function ($timeout) {
        return $timeout(function () {
          /* Set a default content and the content will be replaced as soon as a state is actived */
          return '<p class=\'lead\'>Hello UI-Router About - <ui-view><i>Default content before a state activated</i></ui-view></p>';
        }, 1000);
      }
    })

    .state('books', {
      abstract: true,
      url: '/books',
      templateUrl: 'books.html',
      resolve: {
        /* Inject books service */
        books: ['books', function (books) {
          return books.all();
        }],
        /* custom made promise */
        greeting: ['$q', function ($q) {
          var deferred = $q.defer();
          deferred.resolve('Say greeting!');
          return deferred.promise;
        }]
      },
      controller: ['$scope', '$state', 'books', 'greeting', 'util', function ($scope, $state, books, greeting, util) {
        $scope.books = books;

        $scope.changeBook = function () {
          var bookIdList = util.getAllBookIds($scope.books);
          var res = bookIdList[Math.floor(Math.random() * bookIdList.length)];
          $state.go('books.detail', {bookId: res, greeting: greeting});
        };
      }]
    })

    .state('books.list', {
      /* If we use '' as url, it means '/books' + ''
       * parent's url + itself's url
       * when the user tries to go to 'books', it will automatically go to 'books.list'.
       */
      url: '',
      views: {
        '': {
          templateUrl: 'book-list.html'
        },
        'hint@': {
          template: 'This is hint from books-list'
        }
      }
    })

    .state('books.detail', {
      url: '/:greeting/{bookId:[0-9]{1,4}}',
      views: {
        '': {
          templateUrl: 'book-detail.html',
          controller: ['$scope', '$stateParams', 'util', function ($scope, $stateParams, util) {
            $scope.book = util.getById($scope.books, $stateParams.bookId);
            $scope.greeting = $stateParams.greeting;
          }]
        },

        /* nested */
        'bookInfo': {
          templateProvider: ['$stateParams', function ($stateParams) {
            return '<small class="muted">Book ID: ' + $stateParams.bookId + '</small>';
          }]
        },

        /* override */
        'hint@': {
          template: 'This is hint from books-detail and has override parent hint'
        }
      }
    });

    /*$urlRouterProvider.otherwise(function ($injector, $location) {
      $location.path('/');
    });*/

    $urlRouterProvider.otherwise('/home');
}])

.run(['$rootScope', '$state', function ($rootScope, $state) {
  $rootScope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {
    $rootScope.currentState = $state.current.name;
    $rootScope.toState = toState;
    $rootScope.toParams = toParams;
    $rootScope.fromState = fromState;
    $rootScope.fromParams = fromParams;
  });
}]);
