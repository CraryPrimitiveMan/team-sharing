<?php

namespace backend\models;

use yii\base\Behavior;
use Yii;

class MyBehavior2 extends Behavior
{
    public $hobby = 'basketball<br><br>';

    public function test()
    {
        echo 'I am MyBehavior2 test.<br><br>';
    }

    public function say()
    {
        echo 'MyBehavior2 is saying.<br><br>';
    }

    public function events()
    {
        $processer = new Processer();
        return [
            'func1' => 'sing'
        ];
    }

    public function sing($event)
    {
        echo 'MyBehavior2 is singing.<br><br>';
    }


}
