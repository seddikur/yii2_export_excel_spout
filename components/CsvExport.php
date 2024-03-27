<?php

namespace app\components;

use yii\helpers\VarDumper;

/**
 * Это всего лишь пример.
 *
 * Использование   echo \app\components\CsvExport::widget([
 * 'data' => $array,
 * ]);
 *
 *  $array = [
 * [
 * "name" => 20,
 * "age" => 30,
 * "address" => 123
 * ],
 * [
 * "name" => 20,
 * "age" => 90,
 * "address" => 1993
 * ],
 * ];
 *
 */
class CsvExport extends \yii\base\Widget
{
    /**  @var array Строки данных */
    public $data = [];

    /**
     * Загружать при загрузке страницы
     * и саму страницу
     * @var bool Download csv
     */
    public $download = false;

    /** @var string каталог, в который следует сохранить csv-файл */
    public $saveDir = "";

    /**  @var string  имя файла */
    public $filename = "export.csv";

    /**  @var string Разделитель */
    public $delimiter = ";";
    /**
     * @var bool Соответствуют ли значения и поля utf8_decode или нет, значение по умолчанию true
     */
    public $utf8_decode = true;

    /**
     * Инициализировать виджет
     */
    public function init()
    {
        parent::init();
        //если директория не указана то сохраняем в /runtime/export
        if (!$this->saveDir) $this->saveDir = \Yii::$app->basePath . "/runtime/export";
        //если нет директории создаем
        if (!file_exists($this->saveDir)) mkdir($this->saveDir, 0777, true);
    }

    /**
     * запустить виджет
     */
    public function run()
    {
        $filename = $this->filename;
        $res = $this->data;

        $campos = false;

        if ($this->download) {
            $fp = fopen('php://output', 'w');
            header("Content-type: application/vnd.ms-excel;charset=utf-8");
            header('Content-Disposition: attachment;filename=' . $filename);
        } else {
            $fp = fopen($this->saveDir . "/" . $this->filename, 'w');
        }
        if ($this->utf8_decode) {
            fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));//Añade BOM UTF-8
        }

        foreach ($res as $data) {
            if (!$campos) {
                $campos = array_keys($data);
                //fputcsv — Форматирует строку в виде CSV и записывает её в файловый указатель
                fputcsv($fp, $campos, $this->delimiter);
            }
            fputcsv($fp, $data, $this->delimiter);
        }
        fclose($fp);
    }
}