<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\SignupForm;
use app\models\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','upload','respons'],
                'rules' => [
                    [
                        'actions' => ['logout','upload','respons'],
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
     * {@inheritdoc}
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

    public function actionRespons($inf){
        return substr($inf,1,3);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
//        var_dump(Yii::$app->request->post());
//

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $_SESSION['full_dir_path'] = $model->getUser()->id;
            $_SESSION['dir_path'] = $model->getUser()->id;
            $_SESSION['full_dir_path_arr'] = [$model->getUser()->id];
//            $_SESSION['full_dir_path_arr']= intval($model->getUser()->id);
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionUpload()
    {

        $model = new UploadForm();

//        $path = Yii::getAlias('@webroot');
//        $path.='/uploads/' . $_SESSION['full_dir_path'];
//        var_dump($path); die();
//        chdir($path);
        if (Yii::$app->request->isPost) {

            unset($_GET['param']);
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->upload()) {
                // file is uploaded successfully

//                return $this->render('upload', ['model' => $model]);
            }


            if (isset($_POST['new_dir']) and isset($_SESSION['full_dir_path'])){

                FileHelper::createDirectory('uploads/' . $_SESSION['full_dir_path'] .'/'. $_POST['new_dir']);

           }
        }

    // Экспериментальная часть




        $session = $_SESSION['full_dir_path_arr'];


        if(isset($_GET['param']) and $_GET['param'] == '..' and count($session) > 1){ array_splice($session, -1,1);}
        if(isset($_GET['param']) and ($_GET['param'] != '..')){ $session[] = $_GET['param'];}
//        $session[] = !isset($_GET['param'])? :$_GET['param'];
        $_SESSION['full_dir_path_arr'] = $session;
        $_SESSION['full_dir_path'] = 0;
        $str_session = '';
//        var_dump($session);
//        die();
        foreach ($session as $item){
            $str_session .= $item .'/';
        }
        $_SESSION['full_dir_path'] = $str_session;
//        var_dump($_SESSION['full_dir_path']);
//        var_dump($session);
//var_dump($_SESSION['full_dir_path_arr']);
//die();



        $path = Yii::getAlias('@webroot');
        $path.='/uploads/' . $_SESSION['full_dir_path'];

        $tree = scandir($path);
        chdir($path);
        $table = '<table class="table table-striped">';

        foreach ($tree as $key=>$item){

            if (is_dir($item) && $item !='.'){

                $table .= "<tr><td><a href='upload?param=$item' class='file-link' data-inf='$item'>$item</a></td><td><a href='' class='delete'>Delete</a></td></tr>";

            }else{
                if($item !='.') {$table .= "<tr><td>$item</td><td><a href='' class='delete'>Delete</a></td></tr>";}
            }
        }
        $table .= '</table>';

        return $this->render('upload', ['model' => $model, 'table' => $table]);
    }



    public function actionSignup(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model_dir ='';
        $model = new SignupForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $user = new User();
            $user->name = $model->name;
            $user->login = $model->login;
            $user->parol = $model->parol;
            if($user->save()){
                return $this->goHome();
            }
        }

        return $this->render('signup', ['model'=>$model,'model_dir'=>$model_dir]);
    }




}
