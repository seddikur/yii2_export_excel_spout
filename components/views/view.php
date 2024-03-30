<?php
use yii\helpers\Html;

/** @var  $filename */
echo  "</br>";
echo  'Файл уже стоздан, при нажатии на кнопку происходит скачиваниее';
echo  "</br>";
echo  Html::a(' Скачать Excel',
//                ['app/runtime/export/data.xlsx'],
     ['download', 'filename'=>$filename],
    ['class' => 'btn btn-success']
);

