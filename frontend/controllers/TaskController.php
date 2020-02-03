<?php

namespace frontend\controllers;

use common\models\TaskSubscriber;

use Yii;
use common\models\Task;
use frontend\models\search\TaskSearch;
use yii\filters\AccessControl;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * Class TaskController
 * @package frontend\controllers
 */
class TaskController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['view', 'create', 'update', 'delete', 'index', 'subscribe', 'unsubscribe'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $isSubscribed = TaskSubscriber::isSubscribed(\Yii::$app->user->id, $id);

        return $this->render('view', [
            'model' => $model,
            'isSubscribed' => $isSubscribed,
        ]);
    }
    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $templates = Task::find()->where(['is_template' => true])->all();
        $templates = ArrayHelper::map($templates, 'id', 'title');

        return $this->render('create', ['model' => $model,
            'templates' => $templates
        ]);
    }
    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $templates = Task::find()->where(['is_template'=>true])->all();
        $templates = ArrayHelper::map($templates, 'id', 'title');

        return $this->render('update', [
            'model' => $model,
            'templates' => []
        ]);
    }
    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     */
    public function actionSubscribe($id)
    {
        if (TaskSubscriber::subscribe(\Yii::$app->user->id, $id)) {
            Yii::$app->session->setFlash('success', 'Subscribed');
        } else {
            Yii::$app->session->setFlash('error', 'Error');
        }
        $this->redirect(['task/view', 'id' => $id]);
    }

    /**
     * @param $id
     */
    public function actionUnsubscribe($id)
    {
        if (TaskSubscriber::unsubscribe(\Yii::$app->user->id, $id)) {
            Yii::$app->session->setFlash('success', 'Subscribed');
        } else {
            Yii::$app->session->setFlash('error', 'Error');
        }
        $this->redirect(['task/view', 'id' => $id]);
    }

    /**
     * @param $id
     * @return Task|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        //if (($model = Task::findOne(['id' => $id, 'author_id' => Yii::$app->user->identity->id])) !== null) {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}