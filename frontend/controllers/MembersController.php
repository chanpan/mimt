<?php

namespace frontend\controllers;

use appxq\sdii\utils\SDdate;
use appxq\sdii\utils\VarDumper;
use backend\modules\booking\models\Booking;
use Yii;
use backend\modules\booking\models\Members;
use frontend\models\MembersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use appxq\sdii\helpers\SDHtml;

/**
 * MembersController implements the CRUD actions for Members model.
 */
class MembersController extends Controller
{

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (in_array($action->id, array('create', 'update', 'delete', 'index'))) {

            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Lists all Members models.
     * @return mixed
     */
    public function actionGetBooking()
    {
        $id = Yii::$app->request->get('id');
        $booking = Booking::find()->where('id=:id', [
            ':id' => $id
        ])->one();
        $html = "";
        if ($booking) {
            $date = isset($booking->date) ? SDdate::mysql2phpDate($booking->date) : '';
            $time = isset($booking->time) ? $booking->time : '';
            $end_date = isset($booking->end_date) ? $booking->end_date : '';
            $location = isset($booking->location) ? $booking->location : '';
            $html .= "<div class='alert alert-info'>วันที่จัดอบรม {$date} เวลาเริ่ม {$time} เวลาสิ้นสุด {$end_date} สถานที่จัดอบรม: {$location}</div>";
        }
        return $html;
    }

    public function actionIndex()
    {
        $searchModel = new MembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Members model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->getRequest()->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Members model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->getRequest()->isAjax) {
            $model = new Members();

            if ($model->load(Yii::$app->request->post())) {
                $model->rstat = 1;
                $model->create_date = date('Y-m-d H:i:s');
                $model->create_by = isset(\Yii::$app->user->id) ? \Yii::$app->user->id : '';
                if ($model->save()) {
                    return \cpn\chanpan\classes\CNMessage::getSuccess('Create successfully');
                } else {
                    return \cpn\chanpan\classes\CNMessage::getError('Can not create the data.');
                }
            } else {
                $model->fname = isset(Yii::$app->user->identity->profile->firstname)?Yii::$app->user->identity->profile->firstname:'';
                $model->lname = isset(Yii::$app->user->identity->profile->lastname)?Yii::$app->user->identity->profile->lastname:'';
                $model->tel = isset(Yii::$app->user->identity->profile->tel)?Yii::$app->user->identity->profile->tel:'';
                $model->email = isset(Yii::$app->user->identity->profile->public_email)?Yii::$app->user->identity->profile->public_email:'';
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }
        } else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Updates an existing Members model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->getRequest()->isAjax) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post())) {
                $model->rstat = 1;
                $model->update_date = date('Y-m-d H:i:s');
                $model->update_by = isset(\Yii::$app->user->id) ? \Yii::$app->user->id : '';
                if ($model->save()) {
                    return \cpn\chanpan\classes\CNMessage::getSuccess('Update successfully');
                } else {
                    return \cpn\chanpan\classes\CNMessage::getError('Can not update the data.');
                }
            } else {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
        } else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Deletes an existing Members model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->getRequest()->isAjax) {
            $model = $this->findModel($id);
            $model->rstat = 3;
            $model->update_date = date('Y-m-d H:i:s');
            $model->update_by = isset(\Yii::$app->user->id) ? \Yii::$app->user->id : '';
            if ($model->save()) {

                return \cpn\chanpan\classes\CNMessage::getSuccess('Delete successfully');
            } else {
                return \cpn\chanpan\classes\CNMessage::getError('Can not delete the data.');
            }
        } else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDeletes()
    {
        if (Yii::$app->getRequest()->isAjax) {

            if (isset($_POST['selection'])) {
                foreach ($_POST['selection'] as $id) {
                    $model = $this->findModel($id);
                    $model = $this->findModel($id);
                    $model->rstat = 3;
                    $model->update_date = date('Y-m-d H:i:s');
                    $model->update_by = isset(\Yii::$app->user->id) ? \Yii::$app->user->id : '';
                    $model->save();
                }
                return \cpn\chanpan\classes\CNMessage::getSuccess('Delete successfully');
            } else {
                return \cpn\chanpan\classes\CNMessage::getError('Can not delete the data.');
            }
        } else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * Finds the Members model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Members the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Members::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
