<?php
use yii\helpers\Html;

/** @var  $filename */
echo  Html::a(' Скачать Excel',
//                ['app/runtime/export/data.xlsx'],
     ['download', 'filename'=>$filename],
    ['class' => 'btn btn-success']
);

