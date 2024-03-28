<?php
namespace app\factories;

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\XLSX\Writer;

/**
 * Class CatalogCategoryFactory
 * @package app\factory
 */
final class ExportFileFactory
{
    /**  @var array Строки данных */
    public $data = [];

    /**
     * Загружать при загрузке страницы
     * и саму страницу
     * @var bool Download
     */
    public $download = false;

    /** @var string каталог, в который следует сохранить файл */
    public $saveDir = "";

    /**  @var string  имя файла */
    public $filename = "export.xlsx";

    /* @var string Тип файла */
    public $writerType;

    /* @var Writer */
    private $writer;

    /* @var bool Проверка новой страницы */
    protected $isRendered = false;
    /**
     * Создание и сохранение файла мз
     *
     */



    public function Export($filename)
    {
        $this->filename =$filename;
        //если директория не указана то сохраняем в /runtime/export
        if (!$this->saveDir) $this->saveDir = \Yii::$app->basePath . "/runtime/export";
        //если нет директории создаем
        if (!file_exists($this->saveDir)) mkdir($this->saveDir, 0777, true);
        try {
            $this->create();
        } catch (\Exception $e) {
            throw $e;
        };
    }

    private function create()
    {
        $writerType = $this->writerType;

        //тип файла
        if ($writerType === null) {
            $writerType = Type::XLSX;
        }

        $this->writer = WriterFactory::create($writerType);

        $filename = $this->filename;

        //Запускает программу записи и открывает ее для приема данных
        $this->writer->openToFile($this->saveDir . DIRECTORY_SEPARATOR . $filename);

        //заполняем файл
        $this->writerTable();

        //Закрывает программу записи. Это также закроет стример, предотвращая появление новых данных
        $this->writer->close();
    }

}