<?php

namespace app\controllers;

use app\widgets\Alert;
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





    public function actionUpload()
    {

        $model = new UploadForm();

        if (Yii::$app->request->isPost) {

            unset($_GET['param']);
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->upload()) {
                // file is uploaded successfully

//                return $this->render('upload', ['model' => $model]);
                $this->redirect('upload');
            }

            if (isset($_POST['new_dir']) and isset($_SESSION['full_dir_path'])){

                FileHelper::createDirectory('uploads/' . $_SESSION['full_dir_path'] .'/'. $_POST['new_dir']);
                $this->redirect('upload');
            }
        }

        // Экспериментальная часть

        $session = $_SESSION['full_dir_path_arr'];


        if(isset($_GET['param']) and $_GET['param'] == '..' and count($session) > 1){ array_splice($session, -1,1);}
        if(isset($_GET['param']) and ($_GET['param'] != '..')){ $session[] = $_GET['param'];}


        $_SESSION['full_dir_path_arr'] = $session;
        $_SESSION['full_dir_path'] = 0;
        $str_session = '';

        foreach ($session as $item){
            $str_session .= $item .'/';
        }
        $_SESSION['full_dir_path'] = $str_session;


        $path = Yii::getAlias('@webroot');
        $path.='/uploads/' . $_SESSION['full_dir_path'];

        chdir($path);

        $tree = array_diff(scandir($path), ['.']);


        return $this->render('upload', ['model' => $model, 'tree' => $tree, 'path' => $path]);
    }


    public function actionDelete(){

        $path =  $_GET['path'] . $_GET['name'];
        function mf_removeDir( $path )
        {
            if ( $content_del_cat = glob( $path.'/*') )
            {
                foreach ( $content_del_cat as $object )
                {
                    if ( is_dir( $object ) ) {
                        mf_removeDir( $object );
                    }
                    else {
                        @chmod( $object, 0777 );
                        unlink( $object );
                    }
                }
            }
            @chmod( $object, 0777 );
            rmdir( $path );
        }
        is_dir($path) ? mf_removeDir($path) : unlink($path);
    }

    public function actionRename(){

        $oldname =  $_GET['path'] . $_GET['name'];
        $newname = $_GET['path'] . $_GET['newname'];
        rename($oldname, $newname);
    }

    public function actionDownload(){
        function file_force_download($file) {
            if (file_exists($file)) {
                // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
                // если этого не сделать файл будет читаться в память полностью!
                if (ob_get_level()) {
                    ob_end_clean();
                }
                // заставляем браузер показать окно сохранения файла
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                // читаем файл и отправляем его пользователю
                readfile($file);
                exit;
            }
        }

            $file = $_GET['path'] . $_GET['name'];

        file_force_download($file);
    }


}
