<?php

namespace backend\models;

use yii\base\Event;
use Yii;

class MyEvent extends Event
{
    public $message;

    public function test()
    {
        echo 'MyEvent test.<br><br>';
    }
}
