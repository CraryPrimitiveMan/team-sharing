(function () {
  angular.module('app')
    .factory('books', BooksInfo)

    .factory('util', BooksUtil);

    /* Inject depends */
    BooksInfo.$injector = ['$http'];

    /**
     * Get all books info
     * @param  $http
     * @return factory
     */
    function BooksInfo($http) {
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
    }

    /**
     * Books utils
     * @return function getById and getAllBooksIds
     */
    function BooksUtil() {
      return {
        getById: getById,
        getAllBookIds: getAllBookIds
      };

      function getById(origin, id) {
        for (var i = 0; i < origin.length; i++) {
          if (origin[i].id == id) {
            return origin[i];
          }
        }

        return null;
      }

      function getAllBookIds(origin) {
        var res = [];

        for (var i = 0; i < origin.length; i++) {
          res.push(origin[i].id);
        }

        return res;
      }
    }
})();
