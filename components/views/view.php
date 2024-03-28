<?php
use yii\helpers\Html;

echo  Html::a(' Скачать',
//                ['app/runtime/export/data.xlsx'],
     ['download', 'filename'=>$filename],
    ['class' => 'btn btn-success']
);

