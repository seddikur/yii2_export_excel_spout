<?php

namespace app\controllers;

use backend\forms\user\RoleForm;
use backend\forms\user\UserForm;
use backend\models\search\UserSearch;
use backend\services\user\UserService;
use common\helpers\RbacHelper;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    protected UserService $userService;

    /**
     * @param string $id
     * @param Module $module
     * @param UserService $userService
     * @param array $config
     */
    public function __construct(
        string $id,
        Module $module,
        UserService $userService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->userService = $userService;
    }

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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
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
     * @return array|string|Response
     */
    public function actionCreate()
    {
        $modelForm = new UserForm();
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $modelForm->load(Yii::$app->request->post());

            return ActiveForm::validate($modelForm);
        }

        if ($modelForm->load(\Yii::$app->request->post())) {
            try {
                $model = $this->userService->create($modelForm->attributes);
                Yii::$app->session->setFlash('success', 'Пользователь успешно создан');
                return $this->redirect(['update', 'id' => $model->id]);
            } catch (\Exception $e) {
                Yii::error($e->getMessage(), 'user');
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'userForm' => $modelForm,
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userForm = new UserForm();
        $userForm->prepareUpdate($model);
        $roleForm = new RoleForm();
        $roleForm->prepareUpdate($model);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $userForm->load(Yii::$app->request->post());

            return ActiveForm::validate($userForm);
        }

        if ($userForm->load(\Yii::$app->request->post())) {
            try {
                $model = $this->userService->update($model, $userForm->attributes);
                Yii::$app->session->setFlash('success', 'Пользователь успешно изменен');
                return $this->redirect(['update', 'id' => $model->id]);
            } catch (\Exception $e) {
                Yii::error($e->getMessage(), 'user');
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'userForm' => $userForm,
            'roleForm' => $roleForm,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
