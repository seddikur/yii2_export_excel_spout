<?php

use app\models\Books;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Books', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php

    /** @var \app\components\MessageComponent */
    Yii::$app->message->display('Привет мир');
    ?>
    <?php
    $array = [
        [
            "name" => 20,
            "age" => 30,
            "address" => 123
        ],
        [
            "name" => 20,
            "age" => 90,
            "address" => 1993
        ],
    ];

    echo \app\components\CsvExport::widget([
        'data' => $array,
        'saveDir' => 'web/export',

    ]);

    echo \app\components\ExportFile::widget([
//        'data' => $array,
     //   'data' => ArrayHelper::toArray($dataProvider->query->all()),
     'data' => ArrayHelper::toArray($dataProvider->getModels()),
        'filename' => 'data.xlsx'
    ]);

    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'author.surname',
            'publish_year',
            //'pages',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Books $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
