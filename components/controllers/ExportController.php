<?php

namespace app\components\controllers;

use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\Border;
use Box\Spout\Writer\Style\BorderBuilder;
use Box\Spout\Writer\Style\Color;
use Box\Spout\Writer\Style\Style;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\XLSX\Writer;
use Yii;
use Dompdf\Dompdf;
use Dompdf\Options;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;


/* @var $dataProvider \yii\data\ActiveDataProvider */


//ini_set("memory_limit", "512M");

class ExportController extends Controller
{
    /* @var Writer */
    private $writer;

    /* @var bool Проверка новой страницы */
    protected $isRendered = false;


    /**
     * @return void
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     */
    public function actionExcel()
    {
        $data = $this->getData();
        $searchModel = $data['searchModel'];
        $dataProvider = $data['dataProvider'];
        $title = $data['title'];
        $tableName = $data['tableName'];
        $fields = $this->getFieldsKeys($searchModel->exportFields());


        //если директория не указана то указываем в /runtime/export
        $saveDir = \Yii::$app->basePath . "/runtime/export";
//        if (!$saveDir) $saveDir = \Yii::$app->basePath . "/runtime/export";

        //если нет директории создаем
        if (!file_exists($saveDir)) mkdir($saveDir, 0777, true);

        $this->writer = WriterFactory::create(Type::XLSX);
        //Запускает программу записи и открывает ее для приема данных
        $this->writer->openToFile($saveDir . DIRECTORY_SEPARATOR . $tableName . '.xlsx');

        //заполняем файл
        $this->writerTable($dataProvider, $searchModel, $fields);
//        try {

        //Закрывает программу записи. Это также закроет стример, предотвращая появление новых данных
        $this->writer->close();
        header('Content-Type: application/vnd.ms-excel');
        header('Cache-Control: max-age=0');
        header("Content-Disposition: attachment; filename=" . $tableName . '.xlsx');

        $file_path = $saveDir . DIRECTORY_SEPARATOR . $tableName . '.xlsx';

        readfile($file_path);
        unlink($file_path);
        exit();
//        } catch (\Exception $e) {
//            echo $e->getMessage(), "\n";
//            die();
//        }

    }

    public function actionCsv()
    {
        $data = $this->getData();
        $searchModel = $data['searchModel'];
        $dataProvider = $data['dataProvider'];
        $tableName = $data['tableName'];
        $fields = $this->getFieldsKeys($searchModel->exportFields());
        $csvCharset = \Yii::$app->request->post('csvCharset');

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        $filename = $tableName . ".csv";
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Content-Transfer-Encoding: binary');
        $fp = fopen('php://output', 'w');

        fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

        if ($fp) {
            $items = [];
            $i = 0;
            foreach ($fields as $one) {
                $items[$i] = $one;
                $i++;
            }
            fputs($fp, implode($items, ',') . "\n");
            $items = [];
            $i = 0;
            foreach ($dataProvider->getModels() as $model) {
                foreach ($searchModel->exportFields() as $one) {
                    if (is_string($one)) {
                        $item = str_replace('"', '\"', $model[$one]);
                    } else {
                        $item = str_replace('"', '\"', $one($model));
                    }
                    if ($item) {
                        $items[$i] = '"' . $item . '"';
                    } else {
                        $items[$i] = $item;
                    }
                    $i++;
                }
                fputs($fp, implode($items, ',') . "\n");
                $items = [];
                $i = 0;
            }
        }
        fclose($fp);
    }

    public function actionWord()
    {
        $data = $this->getData();
        $searchModel = $data['searchModel'];
        $dataProvider = $data['dataProvider'];
        $title = $data['title'];
        $tableName = $searchModel->getTableSchema()->fullName;
        $fields = $this->getFieldsKeys($searchModel->exportFields());

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $sectionStyle = $section->getSettings();
        $sectionStyle->setLandscape();
        $sectionStyle->setBorderTopColor('C0C0C0');
        $sectionStyle->setMarginTop(300);
        $sectionStyle->setMarginRight(300);
        $sectionStyle->setMarginBottom(300);
        $sectionStyle->setMarginLeft(300);
        $phpWord->addTitleStyle(1, ['name' => 'HelveticaNeueLT Std Med', 'size' => 16], ['align' => 'center']); //h
        $section->addTitle('<p style="font-size: 24px; text-align: center;">' . $title ? $title : $tableName . '</p>');

        $table = $section->addTable(
            [
                'name' => 'Tahoma',
                'align' => 'center',
                'cellMarginTop' => 30,
                'cellMarginRight' => 30,
                'cellMarginBottom' => 30,
                'cellMarginLeft' => 30,
            ]);
        $table->addRow(300, ['exactHeight' => false]);
        foreach ($fields as $one) {
            $table->addCell(1500, [
                'bgColor' => 'eeeeee',
                'valign' => 'center',
                'borderTopSize' => 5,
                'borderRightSize' => 5,
                'borderBottomSize' => 5,
                'borderLeftSize' => 5
            ])->addText($searchModel->getAttributeLabel($one), ['bold' => true, 'size' => 10], ['align' => 'center']);
        }
        foreach ($dataProvider->getModels() as $model) {
            $table->addRow(300, ['exactHeight' => false]);
            foreach ($searchModel->exportFields() as $one) {
                if (is_string($one)) {
                    $table->addCell(1500, [
                        'valign' => 'center',
                        'borderTopSize' => 1,
                        'borderRightSize' => 1,
                        'borderBottomSize' => 1,
                        'borderLeftSize' => 1
                    ])->addText($model[$one], ['bold' => false, 'size' => 10], ['align' => 'left']);
                } else {
                    $table->addCell(1500, [
                        'valign' => 'center',
                        'borderTopSize' => 1,
                        'borderRightSize' => 1,
                        'borderBottomSize' => 1,
                        'borderLeftSize' => 1
                    ])->addText($one($model), ['bold' => false, 'size' => 10], ['align' => 'left']);
                }
            }
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $filename = $tableName . "-word-" . date("Y-m-d-H-i-s") . ".docx";
        $objWriter->save($filename);

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.ms-word');
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        flush();
        readfile($filename);
        unlink($filename); // deletes the temporary file
        exit;
    }

    public function actionHtml()
    {
        $data = $this->getData();
        $searchModel = $data['searchModel'];
        $dataProvider = $data['dataProvider'];
        $title = $data['title'];
        $tableName = $data['tableName'];
        $fields = $this->getFieldsKeys($searchModel->exportFields());

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addTitle($title ? $title : $tableName);
        $table = $section->addTable(
            [
                'name' => 'Tahoma',
                'size' => 10,
                'align' => 'center',
            ]);
        $table->addRow(300, ['exactHeight' => true]);
        foreach ($fields as $one) {
            $table->addCell(1500, [
                'bgColor' => 'eeeeee',
                'valign' => 'center',
                'borderTopSize' => 5,
                'borderRightSize' => 5,
                'borderBottomSize' => 5,
                'borderLeftSize' => 5
            ])->addText($searchModel->getAttributeLabel($one), ['bold' => true, 'size' => 10], ['align' => 'center']);
        }
        foreach ($dataProvider->getModels() as $model) {
            $table->addRow(300, ['exactHeight' => true]);
            foreach ($searchModel->exportFields() as $one) {
                if (is_string($one)) {
                    $table->addCell(1500, [
                        'valign' => 'center',
                        'borderTopSize' => 1,
                        'borderRightSize' => 1,
                        'borderBottomSize' => 1,
                        'borderLeftSize' => 1
                    ])->addText('<p style="margin-left: 10px;">' . $model[$one] . '</p>', ['bold' => false, 'size' => 10], ['align' => 'right']);
                } else {
                    $table->addCell(1500, [
                        'valign' => 'center',
                        'borderTopSize' => 1,
                        'borderRightSize' => 1,
                        'borderBottomSize' => 1,
                        'borderLeftSize' => 1
                    ])->addText('<p style="margin-left: 10px;">' . $one($model) . '</p>', ['bold' => false, 'size' => 10], ['align' => 'right']);
                }
            }
        }

        header('Content-Type: application/html');
        $filename = $tableName . ".html";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $objWriter->save('php://output');
    }

    public function actionPdf()
    {
        $data = $this->getData();
        $searchModel = $data['searchModel'];
        $dataProvider = $data['dataProvider'];
        $title = $data['title'];
        $tableName = $data['tableName'];
        $fields = $this->getFieldsKeys($searchModel->exportFields());

        $options = new Options();
        $options->set('defaultFont', 'times');
        $dompdf = new Dompdf($options);
        $html = '<html><body>';
        $html .= '<h1>' . $title ? $title : $tableName . '</h1>';
        $html .= '<table width="100%" cellspacing="0" cellpadding="0">';
        $html .= '<tr style="background-color: #ececec;">';
        foreach ($fields as $one) {
            $html .= '<td style="border: 2px solid #cccccc; text-align: center; font-weight: 500;">' . $searchModel->getAttributeLabel($one) . '</td>';
        }
        $html .= '</tr>';

        foreach ($dataProvider->getModels() as $model) {
            $html .= '<tr>';
            foreach ($searchModel->exportFields() as $one) {
                if (is_string($one)) {
                    $html .= '<td style="border: 1px solid #cccccc; text-align: left; font-weight: 300; padding-left: 10px;">' . $model[$one] . '</td>';
                } else {
                    $html .= '<td style="border: 1px solid #cccccc; text-align: left; font-weight: 300; padding-left: 10px;">' . $one($model) . '</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '</body></html>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($tableName . '_' . time());
    }

    /**
     * Собираем данные
     * @return array
     */
    private function getData()
    {

        $queryParams = Json::decode(\Yii::$app->request->post('queryParams'));
        $searchModel = \Yii::$app->request->post('model');
        $searchModel = new $searchModel;
        $tableName = $searchModel->tableName();
        $dataProvider = $searchModel->search($queryParams);
        $title = \Yii::$app->request->post('title');
        $getAll = \Yii::$app->request->post('getAll');
        if ($getAll) {
            $dataProvider->pagination = false;
        }
        return [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'title' => $title,
            'tableName' => $tableName,
        ];
    }

    private function getFieldsKeys($fieldsSended)
    {
        $fields = [];
        $i = 0;
        foreach ($fieldsSended as $key => $value) {
            if (is_int($key)) {
                $fields[$i] = $value;
            } else {
                $fields[$i] = $key;
            }
            $i++;
        }
        return $fields;
    }

    public function writerTable($dataProvider, $searchModel, $fields)
    {
        //Создает новый лист и делает его текущим листом. Теперь данные будут записаны на этот лист.
        if ($this->isRendered) {
            $this->writer->addNewSheetAndMakeItCurrent();
        }

        //для записи строки столбцов один раз
        $columnsInitialized = false;
        $dataRow = [];
        //формирование массив название столбцов
        foreach ($fields as $field) {
            $dataRow [] = $searchModel->getAttributeLabel($field);
        }

        //формирование массива строк
        foreach ($dataProvider->getModels() as $model) {
            $rowData = [];
            foreach ($searchModel->exportFields() as $one) {

                    if (is_string($one)) {
                        $rowData[] = $model[$one];
                    } else {
                        $rowData [] = $one($model);
                    }
            }

            // записываем шапку
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

                $this->writer->addRowWithStyle($dataRow, $style);
            }

            // записываем строки
            $style = (new StyleBuilder())
                ->setFontSize(12)
                ->setShouldWrapText()
                ->build();
            $this->writer->addRowWithStyle($rowData, $style);
//            $this->writer->addRow($rowData);
        }

//        VarDumper::dump($rowData, 10, true);
//        die();


//        $dd = [
//            "1" => "bar",
//            "2" => "foo",
//            "3" => "bar",
//            "4" => "foo",
//        ];
//        $this->writer->addRow($dd);
//        $row = [
//            "id" => "trererter",
//            "bar" => "gui",
//            "key" => "bafseefr",
//            "ke" => "fetrertoo",
//        ];
//        $this->writer->addRow($row);
        $this->isRendered = true;
        return $this;
    }
}

