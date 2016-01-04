<?php

namespace app\controllers;

use Yii;
use yii\web\UnauthorizedHttpException;
use app\controllers\base\RestController;
use app\models\User;
use app\models\ChargeLog;
use app\models\RefundLog;
use app\models\CampSign;

/**
 * This is controller for TestRbacController.
 * @author Emma Fu
 */
class TestRbacController extends RestController
{
    /**
     * @var string Bind model class "app\models\User".
     */
    public $modelClass = 'app\models\User';

    // public function beforeAction($action)
    // {
    //     $id = $this->getBody();
    //     $action = Yii::$app->controller->action->id;
    //     // if(Yii::$app->user->can($action)){
    //     //     return true;
    //     // }else{
    //     //     throw new UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
    //     // }

    //     if(Yii::$app->user->can($action, $id)){
    //         return true;
    //     }else{
    //         throw new UnauthorizedHttpException('对不起，您现在还没获此操作的权限');
    //     }
    // }

    public function actionUpdatePost()
    {
       return 'update-post';
    }

    public function actionCreatePost()
    {
       return 'create-post';
    }

    public function actionUpdateOwnPost()
    {
        return 'update-own-post';
    }

    public function actionTest()
    {
        return Yii::$app->authManager->getAllApis();
        return Yii::$app->authManager->getForbiddenPermissions('apis', 'charge/*');
    }

    public function actionTestStatistics()
    {
        $token = Yii::$app->resque->enqueueJob('app\jobs\statistics\CampJob', ['startDate' => '2015-12-31', 'endDate' => '2016-01-01']);
        return $token;
        $reportDate = date('Y-m-d', strtotime('+1 day', strtotime('2015-12-31')));

        $total_charged = ChargeLog::getCampTotalCharged(1, 3, strtotime($reportDate))['total_fee'];

        if (!$total_charged) {
            $total_charged = 0;
        }

        return $total_charged;
        // return strtotime($reportDate);
        // return RefundLog::getCampTotalRefunded(1, 1, $reportDate);
        // return ChargeLog::getCampTotalCharged(1, 3, strtotime($reportDate))['total_fee'];
        // return CampSign::getCampTotalSignupCount(1, 1, strtotime($reportDate));
        // return CampSign::getCampTotalSigninCount(1, 1, strtotime($reportDate));
        // return CampSign::getCampTotalSignupChild(1, 1, strtotime($reportDate));
        return CampSign::getCampNewSignupChild(1, 1, strtotime($reportDate));
        // return CampSign::getCampNewSigninChild(1, 1, strtotime($reportDate));
    }

    public function actionJob()
    {
        // $token = Yii::$app->resque->enqueueJob('app\jobs\statistics\CampJob', ['kindergartens' => [1], 'startDate' => '2015-12-1', 'endDate' => '2016-1-3']);
        // echo $token;
        $jobParams = [
              'kindergartenIds' => 1,
              'startDate' => '2015-12-28',
              'endDate' => '2015-12-31'
            ];
        Yii::$app->resque->enqueueJob('app\jobs\statistics\ChildPropertyJob', $jobParams);
    }
}
