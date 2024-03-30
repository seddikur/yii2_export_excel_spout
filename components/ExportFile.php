<?php

namespace app\components;

use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\XLSX\Writer;
use \yii\base\Widget;
use yii\helpers\VarDumper;


/**
 * Использование
 *
 *  echo \app\components\ExportFile::widget([
 * 'data' => ArrayHelper::toArray($dataProvider->getModels()),
 * 'filename' => 'data.xlsx'
 * ]);
 *
 * Ссылка для скачивания в контроллер
 *  public function actionDownload()
 * {
 * $file = \Yii::$app->basePath . "/runtime/export/".'data.xlsx';
 * return \Yii::$app->response->sendFile($file);
 * }
 */
class ExportFile extends Widget
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
        $this->save();
        return $this->render(
            'view',
             [
                'filename' =>$this->filename
            ]
        );
    }

    private function save()
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

    /**
     * @return $this
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\SpoutException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function writerTable()
    {
        $data_array = $this->data;

        //Создает новый лист и делает его текущим листом. Теперь данные будут записаны на этот лист.
        if ($this->isRendered) {
            $this->writer->addNewSheetAndMakeItCurrent();
        }

        //для записи строки столбцов один раз
        $columnsInitialized = false;

        foreach ($data_array as $value) {
            //запись название столбцов
            if (!$columnsInitialized) {
                $columnsInitialized = true;

                $border = (new BorderBuilder())
                    ->setBorderBottom(Color::BLACK, Border::WIDTH_MEDIUM, Border::STYLE_SOLID)
                    ->build();

                $style = (new StyleBuilder())
                    ->setFontBold()
                    ->setFontSize(12)
                    ->setFontColor(Color::BLACK)
                    ->setShouldWrapText()
                    ->setBorder($border)
                    ->build();
                $this->writer->addRowWithStyle(array_keys($value), $style);
//                $this->writer->addRow(array_keys($value));
            }

            //запись строк
            $rowData = [];
            foreach ($value as $column) {

                $columnValue = $column;
                $rowData[] = $columnValue;
            }
//            VarDumper::dump($rowData, 10, true);
//            die();
            $this->writer->addRow($rowData);
        }
        $this->isRendered = true;
        return $this;
    }

}
