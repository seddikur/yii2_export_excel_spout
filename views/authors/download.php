<?php

use yii\helpers\ArrayHelper;
use app\components\ExportFile;

/**
 * Виджет работает таким образом,
 * что при загрузке страницы создается файл
 */


/** @var string $filename */
/** @var array $dataProvider */

echo ExportFile::widget([
    'filename' => $filename,
    'data' => $dataProvider,
//     'data' => ArrayHelper::toArray($dataProvider->getModels()),

]);

