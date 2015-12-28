<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Person;
use backend\models\MyBehavior;
use backend\models\MyBehavior2;

/**
 * Weibo controller
 */
class BehaviorController extends Controller
{
    /**
     * 类的混合
     */
    public function actionIndex()
    {
        $person = new Person();
        $person1 = new Person();
        $myBehavior = new MyBehavior();
        $person->attachBehavior('myBeh', $myBehavior);
        $person1->attachBehavior('myBeh1', new MyBehavior());

        $person->test();
        echo $person->message;
        $person->message = "I am MyBehavior attribute: message.<br><br>";
        /*echo $person->message;*/
        echo $person1->message;

        /*protected*/
        /*echo $person->message1;*/
    }

    /*对象的混合*/
    public function actionMixins()
    {
        $person = new Person();

        $myBehavior = new MyBehavior();
        $myBehavior2 = new MyBehavior2();

        $person->attachBehaviors([
            'myBeh' => $myBehavior,
            'myBeh2' => $myBehavior2
        ]);

        echo $person->hobby;

        echo $person->message;

        /*print_r($myBehavior->owner);
        print_r($person->getBehavior('myBeh'));*/

        /*移除对象*/
        /*$person->detachBehavior('myBeh');
        $person->detachBehaviors();*/

        $person->test();

    }

    /*类的混合-事件响应*/
    public function actionEvent()
    {
        $person = new Person();
        $person->trigger('func1');
        $person->trigger('func2');
        $person->trigger('func3');
    }
}
