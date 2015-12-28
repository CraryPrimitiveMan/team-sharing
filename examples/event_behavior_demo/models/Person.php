<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\MyEvent;
use backend\models\MyBehavior;
use backend\models\MyBehavior2;
use yii\base\Event;

class Person extends Model
{
    const ACTION_SEND = 'send';
    const ACTION_EAT = 'eat';
    const SEPARATOR = '-----------------------------------------------<br><br>';

    public function send()
    {
        $myEvent = new MyEvent();
        $myEvent->message = 'hello, I am an event message.<br><br>';
        echo 'Person is sending one message.<br><br>';
        //给Event类绑定数据进行传参
        $this->trigger(self::ACTION_SEND, $myEvent);
    }

    public function order()
    {
        $myEvent = new MyEvent();
        echo 'Person is learning the order of event.<br><br>';
        echo self::SEPARATOR;
        $this->trigger('order1', $myEvent);
        echo self::SEPARATOR;
        $this->trigger('order2');
        echo self::SEPARATOR;
        $this->trigger('order3');
    }

    public function order2()
    {
        echo 'Person => order2.<br><br>';
    }

    public function order3()
    {
        echo 'Person => order3.<br><br>';
    }

    public function eat()
    {
        echo 'This is person eating.<br><br>';
       /* $this->trigger(self::ACTION_EAT);*/
        Event::trigger(Person::className(), self::ACTION_EAT);
    }

    public function app()
    {
        echo 'Person => global events.<br><br>';
        Yii::$app->trigger('app');
    }

    /*-----------------------------Behavior------------------------------------*/

    /*类的混合*/
    public function behaviors()
    {
        return [
            /*MyBehavior::className(),*/

            /*[
                'class' => MyBehavior::className(),x
                'message' => 'MyBehavior->message.<br><br>'
            ],
            'myBehavior2' => MyBehavior2::className()*/
        ];
    }
}
