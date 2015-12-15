define([], function () {
    return {
        defaultRoute: '/demo1',
        routes: {
            '/demo1': {
                templateUrl: 'components/demo1/demo1.html',
                controller: 'hrm.demo1',
                dependencies: ['components/demo1/demo1'],
                allowAnonymous: true
            },
            '/demo2': {
                templateUrl: 'components/demo2/demo2.html',
                controller: 'hrm.demo2',
                dependencies: ['components/demo2/demo2'],
                allowAnonymous: true
            },
            '/demo3': {
                templateUrl: 'components/demo3/demo3.html',
                controller: 'hrm.demo3',
                dependencies: ['components/demo3/demo3'],
                allowAnonymous: true
            }
        }
    };
});
