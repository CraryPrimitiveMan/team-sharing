<?php

namespace backend\models;

use yii\base\Behavior;
use backend\models\Processer;
use Yii;

class MyBehavior extends Behavior
{
    public $message = 123;

    /*子类使用父类中protected属性，这是继承关系，但Behavior是注入方以及被注入方的关系，故不可访问。*/
    protected $message1 = 'message1';

    public function test()
    {
        echo 'I am MyBehavior test.<br><br>';
    }

    public function events()
    {
        $processer = new Processer();
        return [
            'func1' => 'sing',
            'func2' => [$processer, 'cook'],
            'func3' => function($event) {
                echo 'This is func3.<br><br>';
            }
        ];
    }

    public function sing($event)
    {
        /*print_r($event->sender);*/
        echo 'MyBehavior is singing.<br><br>';
    }
}
