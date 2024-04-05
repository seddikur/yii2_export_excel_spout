<?php

use app\models\Books;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BooksSearch $searchModel */
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

    /**
     * Это тестовый виджет для примера
     */
    echo \app\components\CsvExport::widget([
        'data' => $array,
        'saveDir' => 'web/export',

    ]);

    /**
     * Виджет работает таким образом,
     * что при загрузке страницы создается файл
     */
    echo \app\components\ExportFile::widget([
//        'data' => $array,
        'data' => ArrayHelper::toArray($dataProvider->query->all()),
//     'data' => ArrayHelper::toArray($dataProvider->getModels()),
        'filename' => 'booksFile.xlsx'
    ]);

    ?>

    <?php
    echo \app\components\ExportPhp::widget([
        'model' => 'app\models\BooksSearch',   // путь к модели
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


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
//            'author.surname',
            [
                'attribute' => 'author_id',
                'format' => 'text',
                'value' => function ($model) {
                    /* @var $model \app\models\Authors */
                    return $model->author->surname;
                },
            ],
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
