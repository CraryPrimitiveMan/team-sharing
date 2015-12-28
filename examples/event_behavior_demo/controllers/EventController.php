<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Person;
use backend\models\Processer;
use backend\models\Child;
use yii\base\Event;

/**
 * Weibo controller
 */
class EventController extends Controller
{
    /**
     * 事件响应
     */
    public function actionIndex()
    {
        $person = new Person();
        $processer = new Processer();

        $person->on(Person::ACTION_SEND, [$processer, 'push']);
        /*$person->on(Person::ACTION_SEND, [Processer::className(), 'push']);*/

        $person->send();
    }

    /**
     * 事件的处理顺序
     */
    public function actionOrder()
    {
        echo 'Hello, world.<br><br>';

        $person = new Person();
        $processer = new Processer();

        /*顺序执行依次被绑定的事件*/
        $person->on('order1', [$processer, 'order1'], 'order1-data');

        $person->on('order2', function($event) {
            echo 'Anonymous => order2.<br><br>';

            /*设置参数$handled, 停止执行此事件的后续处理程序*/
            /*$event->handled = true;*/
        });

        $person->on('order2', [$person, 'order2']);

        $person->on('order3', [Processer::className(), 'order3']);

        /*$person->on('order3', [$person, 'order3']);*/

        /*给参数$append传递false， 以便这个事件可以最先被调用*/
        $person->on('order3', [$person, 'order3'], '', false);


        /*事件解绑*/
        /*$person->off('order2', [$person, 'order2']);*/
        /*$person->off('order2');*/

        /*对于匿名函数的解绑*/
        $person->off('order2', function($event) {
             echo 'Anonymous => order2.<br><br>';
        });

        $anonymous = function($event) {
            echo 'Anonymous<br><br>';
        };
        $person->on('order2', $anonymous);
        $person->off('order2', $anonymous);

        $person->order();
    }

    /**
     * 类级事件的程序处理
     */
    public function actionClass()
    {
        $person1 = new Person();
        $person2 = new Person();
        $processer = new Processer();
        $child = new Child();

        /*$person1->on(Person::ACTION_EAT, [$processer, 'cook']);
        $person2->on(Person::ACTION_EAT, [$processer, 'cook']);*/

        /*类级事件处理*/
        Event::on(Person::className(), Person::ACTION_EAT, [$processer, 'cook']);

        $person1->on(Person::ACTION_EAT, [$processer, 'order3']);

        $person1->eat();

        echo Person::SEPARATOR;

        $person2->eat();

        echo Person::SEPARATOR;

        $child->eat();
    }

    /**
     * 全局事件的响应
     */
    public function actionApp()
    {
        /*Yii::$app->on('app', function ($event) {
            echo get_class($event->sender);
        });

        $person = new Person();
        $person->app();*/

        Yii::$app->on(\yii\base\Application::EVENT_AFTER_REQUEST, function() {
            echo 'Event after request.<br><br>';
        });

        echo 'The function is running.<br><br>';
    }
}
