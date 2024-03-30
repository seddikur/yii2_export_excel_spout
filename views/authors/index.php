<?php

use app\models\Authors;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\models\AuthorsSearch $searchModel */

$this->title = 'Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Authors', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?php
        echo \app\components\ExportPhp::widget([
            'model' => 'app\models\AuthorsSearch',   // путь к модели
            // 'searchAttributes'  => $searchModel,                    // фильтр
            'title' => 'Заголовок документа',
            'queryParams' => Yii::$app->request->queryParams,


            'getAll' => true,                               // все записи - true, учитывать пагинацию - false
            'csvCharset' => 'Windows-1251',                      // кодировка csv файла: 'UTF-8' (по умолчанию) или 'Windows-1251'

            'buttonClass' => 'btn btn-primary',                   // класс кнопки
            'blockClass' => 'pull-left',                         // класс блока в котором кнопка
            'blockStyle' => 'padding: 5px;',                     // стиль блока в котором кнопка

            // экспорт в следующие файлы (true - разрешить, false - запретить)
            'xls' => true,
            'csv' => true,
            'word' => false,
            'html' => true,
            'pdf' => false,

            // шаблоны кнопок
            'xlsButtonName' => 'Excel',
            'csvButtonName' => 'CSV',
            'wordButtonName' => 'Word',
            'htmlButtonName' => 'HTML',
            'pdfButtonName' => 'PDF'


        ]) ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'surname',
            'name',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Authors $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
