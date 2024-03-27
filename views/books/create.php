<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Books $model */
/** @var backend\models\Authors $authors */

$this->title = 'Новая книга';
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="books-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'authors' => $authors,
    ]) ?>

</div>
