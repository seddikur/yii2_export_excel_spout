<?php

namespace app\controllers;

use app\factories\ExportFileFactory;
use app\models\Authors;
use app\models\AuthorsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthorsController implements the CRUD actions for Authors model.
 */
class AuthorsController extends Controller
{

    public $dataProvider;
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Authors models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AuthorsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Authors model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Authors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Authors();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Authors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Authors model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Authors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Authors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Authors::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
//
//    public function actionExcel()
//    {
//        $data = $this->getData();
//        $searchModel    = $data['searchModel'];
//        $dataProvider   = $data['dataProvider'];
//        $title          = $data['title'];
//        $tableName      = $data['tableName'];
//        $fields         = $this->getFieldsKeys($searchModel->exportFields());
//
//        $objPHPExcel = new \PHPExcel();
//        $objPHPExcel->setActiveSheetIndex(0);
//        $objPHPExcel->getActiveSheet()->setTitle($title ? $title : $tableName);
//        $letter = 65;
//        foreach ($fields as $one) {
//            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($letter))->setAutoSize(true);
//            $letter++;
//        }
//        $letter = 65;
//        foreach ($fields as $one) {
//            $objPHPExcel->getActiveSheet()->setCellValue(chr($letter).'1', $searchModel->getAttributeLabel($one));
//            $objPHPExcel->getActiveSheet()->getStyle(chr($letter).'1')->getAlignment()->setHorizontal(
//                \PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $letter++;
//        }
//        $row = 2;
//        $letter = 65;
//        foreach ($dataProvider->getModels() as $model) {
//            foreach ($searchModel->exportFields() as $one) {
//                if (is_string($one)) {
//                    $objPHPExcel->getActiveSheet()->setCellValue(chr($letter).$row,$model[$one]);
//                    $objPHPExcel->getActiveSheet()->getStyle(chr($letter).$row)->getAlignment()->setHorizontal(
//                        \PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                } else {
//                    $objPHPExcel->getActiveSheet()->setCellValue(chr($letter).$row,$one($model));
//                    $objPHPExcel->getActiveSheet()->getStyle(chr($letter).$row)->getAlignment()->setHorizontal(
//                        \PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//                }
//                $letter++;
//            }
//            $letter = 65;
//            $row++ ;
//        }
//
//        header('Content-Type: application/vnd.ms-excel');
//        $filename = $tableName.".xls";
//        header('Content-Disposition: attachment;filename='.$filename);
//        header('Cache-Control: max-age=0');
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save('php://output');
//    }
}
