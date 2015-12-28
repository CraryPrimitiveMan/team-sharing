<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Processer model
 */
class Processer extends Model
{

    public function push($myEvent)
    {
        echo $myEvent->message;
        echo 'Processer is pushing the message.<br><br>';
    }

    public function order1($myEvent)
    {
        echo 'Processer => order1.<br><br>';
        echo 'Get order1 name: '.$myEvent->name.'<br><br>';
        echo 'Get order1 data: '.$myEvent->data.'<br><br>';
    }

    public function order3()
    {
        echo 'Processer => order3.<br><br>';
    }

    public function cook($event)
    {
        echo 'Processer is cooking.<br><br>';
    }
}
