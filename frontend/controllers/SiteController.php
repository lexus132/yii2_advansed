<?php
namespace frontend\controllers;

use app\models\Test;
//use frontend\models\Test;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

use frontend\models\News;

/**
 * Site controller
 */
class SiteController extends Controller
{
    protected $baseCount = 5;
    protected $addCount = 2;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
	$model = News::find()->where(['enabled' => 1])->orderBy(['id' => SORT_DESC])->limit(5)->all();
        return $this->render('index', [ 'model' => $model]);
    }

    public function actionLoadMore($next = null)
    {
	if(\Yii::$app->request->isPost){
	    $next = (!empty($next))? (int)$next : 1;
	    $offset = $this->baseCount + $this->addCount*$next;
	    $model = News::find()->orderBy(['id' => SORT_DESC])->offset($offset)->limit($this->addCount)->all();
	    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	    if(!empty($model) and is_array($model) and (count($model) > 0)){
		$items = [
		    'count' => $this->addCount,
		    'successful' => \Yii::$app->view->renderFile('@frontend/views/site/itemNews.php', ['model' => $model])
		];
	    } else {
		$items = [
		    'count' => 0,
		];
	    }
	    return $items;
	}
    }

    public function actionSearch()
    {
	if(\Yii::$app->request->isPost){
	    $search = (!empty(\Yii::$app->request->post('search')))? \Yii::$app->request->post('search') : '';
	    if(!empty($search)){
		$searchArr = array();
		$searTemp = explode(' ', $search);
		foreach ($searTemp as $searItem){
		    if(!empty(trim(strip_tags($searItem))) and ( count($searchArr) < 4))
		    $searchArr[] = trim(strip_tags($searItem));
		}
	    }
	    $rezult = '';
	    if(!empty($searchArr)){
		$rezult = News::find()->where(['enabled' => 1])->andWhere(['like', 'description', $searchArr])->orderBy(['publish_date' => SORT_DESC])->limit(5)->all();
	    }
	    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	    if(!empty($rezult) and is_array($rezult) and (count($rezult) > 0)){
		$items = [
		    'successful' => \Yii::$app->view->renderFile('@frontend/views/site/itemNews.php', ['model' => $rezult])
		];
	    } else {
		$items = [
		    'nodate' => '',
		];
	    }
	    return $items;
	}
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
//    public function actionLogin()
//    {
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            return $this->goBack();
//        } else {
//            return $this->render('login', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
//    public function actionLogout()
//    {
//        Yii::$app->user->logout();
//
//        return $this->goHome();
//    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
//    public function actionContact()
//    {
//        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
//                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
//            } else {
//                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
//            }
//
//            return $this->refresh();
//        } else {
//            return $this->render('contact', [
//                'model' => $model,
//            ]);
//        }
//    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
//    public function actionAbout()
//    {
//        return $this->render('about');
//    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
//    public function actionRequestPasswordReset()
//    {
//        $model = new PasswordResetRequestForm();
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            if ($model->sendEmail()) {
//                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
//
//                return $this->goHome();
//            } else {
//                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
//            }
//        }
//
//        return $this->render('requestPasswordResetToken', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
//    public function actionResetPassword($token)
//    {
//        try {
//            $model = new ResetPasswordForm($token);
//        } catch (InvalidParamException $e) {
//            throw new BadRequestHttpException($e->getMessage());
//        }
//
//        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
//            Yii::$app->session->setFlash('success', 'New password saved.');
//
//            return $this->goHome();
//        }
//
//        return $this->render('resetPassword', [
//            'model' => $model,
//        ]);
//    }
}
