<?php

namespace backend\controllers;

use Yii;
use common\models\News;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\models\RssCode;
use yii\web\UploadedFile;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    protected $error = array();

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
	    'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
			'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->orderBy(['id' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();
	
        if ($model->load(Yii::$app->request->post())) {
	    $image = UploadedFile::getInstance($model, 'image_file');
	    if(!empty($image)){
		$ext = explode(".", $image->name)[1];
		$model->image = Yii::$app->security->generateRandomString().".{$ext}";
		$path = Yii::$app->params['uploadPath'] . $model->image;
	    }
	    if($model->save()){
                if(!empty($image)) $image->saveAs($path);
                return $this->redirect(['view', 'id'=>$model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
	    $image = UploadedFile::getInstance($model, 'image_file');
	    if(!empty($image)){
		$ext = explode(".", $image->name)[1];
		$model->image = Yii::$app->security->generateRandomString().".{$ext}";
		$path = Yii::$app->params['uploadPath'] . $model->image;
	    }
	    if($model->save()){
                if(!empty($image)) $image->saveAs($path);
                return $this->redirect(['view', 'id'=>$model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionParser(){
	foreach($this->parse('https://sport.sme.sk/rss') as $newsItem){
	    $exist = News::findOne(['title' => $newsItem['title'], 'publish_date' => date('Y-m-d', $newsItem['publicationDate'])]);
	    if(empty($exist->id)){
		$img = '';
		if(!empty($newsItem['img'])){
		    if(!is_file(\Yii::$app->params['uploadPath'].$newsItem['publicationDate'].'.jpeg')){
			$img    = $newsItem['publicationDate'].'.jpeg';
		    } else {
			$adder = rand(1, 9999);
			$img    = $newsItem['publicationDate'].$adder.'.jpeg';
		    }
		    $file   = file($newsItem['img']);
		    $result = file_put_contents(\Yii::$app->params['uploadPath'].$img, $file);
		}
		$model = new News([
		    'title' => $newsItem['title'],
		    'link' => $newsItem['link'],
		    'description' => implode('<br>', $newsItem['description']),
		    'publish_date' => date('Y-m-d', $newsItem['publicationDate']),
		    'image' => $img,
		    ]);
		$model->save();
	    }
	}
	
	\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	if(empty($this->error)){
	    $items = ['successful' => 'true'];
	} else {
	    $items = ['errors' => $this->error];
	}
	return $items;
    }
    
    protected function parse($url, $cssClass = '') {
	$rssCode = RssCode::parseCode($url);
	foreach ($rssCode as $key => $oneRssCode) {
	    $textDeleteHtmlTags = RssCode::deleteHtmlTags($oneRssCode['description']);
	    $textUpdateExternalLink = RssCode::convertCodeExternalLinks($textDeleteHtmlTags, $cssClass);
	    $devideText = RssCode::devideTextByTag($textUpdateExternalLink);
	    $rssCode[$key]['description'] = $devideText;
	}

	return $rssCode;
    }
}
