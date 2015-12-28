<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use app\models\Person;

use backend\models\Cat;
use backend\models\Mouse;
use backend\models\Dog;
use yii\base\Event;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['event', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        $cat = new Cat();
        $cat2 = new Cat();
        $mouse = new Mouse();
        $dog = new Dog();

        $cat->on('miao', [$mouse, 'run']);
        $cat->on('miao', [$dog, 'look']);

        /*class-level event*/
        /*Event::on(Cat::className(), 'miao', [$mouse, 'run']);*/

        $cat->shout();
        $cat2->shout();
    }

    /**
     * 事件处理
     */
    public function actionEvent(){
        echo '这是事件处理<br/>';

        $person = new Person();

        $this->on('SayHello', [$person,'say_hello'],'你好，朋友');

        $this->on('SayGoodBye', ['app\models\Person','say_goodbye'],'再见了，我的朋友');

        $this->on('GoodNight', function(){
            echo '晚安！';
        });

        $this->trigger('SayHello');
        $this->trigger('SayGoodBye');
        $this->trigger('GoodNight');

    }
}
