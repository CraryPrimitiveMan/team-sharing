angular.module('app')
  .factory('books', ['$http', function ($http) {

    var books = $http({method: 'GET', url: 'assets/books.json'})
      .then(function (resp) {
        /* return promise */
        return resp.data.books;
    });

    var factory = {};

    factory.all = function () {
      return books;
    };

    return factory;
  }])

  .factory('util', function () {
    return {
      getById: function getById(origin, id) {
        for (var i = 0; i < origin.length; i++) {
          if (origin[i].id == id) {
            return origin[i];
          }
        }

        return null;
      },

      getAllBookIds: function getAllBookIds(origin) {
        var res = [];

        for (var i = 0; i < origin.length; i++) {
          res.push(origin[i].id);
        }

        return res;
      }
    };
  });
