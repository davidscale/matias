<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\base\InvalidArgumentException;


use common\models\User;
use common\models\LoginForm;
use common\models\SignupForm;
use common\models\VerifyEmailForm;
use common\models\ResetPasswordForm;
use common\models\PasswordResetRequestForm;


/**
 * Site controller
 */
class SiteController extends Controller
{
    //img unlz
    public $imagen = 'https://yt3.ggpht.com/ytc/AKedOLSLRjKHopJL3YRWbbF4mVQKGLRLB4TiXOK-POE3dw=s900-c-k-c0x00ffffff-no-rj';
    //img edf
    public $imagen_0 = 'https://scontent.faep8-1.fna.fbcdn.net/v/t1.6435-9/73417813_2587000121366243_906787829500084224_n.jpg?_nc_cat=103&ccb=1-5&_nc_sid=09cbfe&_nc_ohc=-hAZjS-wQhMAX8L2f9Q&_nc_ht=scontent.faep8-1.fna&oh=00_AT_WBcDKz73xhFIA9YAgo8lMC8pJ8jG2YR9AqxtKe0OxWg&oe=62351216';
    public $imagen_1 = 'https://media-exp1.licdn.com/dms/image/C561BAQHWc14MS-vB4w/company-background_10000/0/1519800673124?e=2159024400&v=beta&t=LqiRJQXOwnbijVuOPETYkwtAVF85a4hwGf2omWtjEj4';







    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'], // un usuario
                    ],
                    [
                        'actions' => ['login', 'request-password-reset', 'reset-password', 'verify-email'],
                        'allow' => true,
                        'roles' => ['?'], //invitado
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
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
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
             return $this->goHome();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
            'imagen' => $this->imagen,
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


    public function actionSignup()
    { 
        
        $this->layout = 'blank';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Gracias por registrarse. Por favor revise su bandeja de entrada para el correo electrónico de verificación.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
            'imagen' => $this->imagen,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'blank';

        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Revise su correo electrónico para obtener más instrucciones.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Lo sentimos, no podemos restablecer la contraseña de la dirección de correo electrónico proporcionada.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
            'imagen' => $this->imagen,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($t)
    {
        $this->layout = 'blank';
        // var_dump($_GET);die;
        $token = $_GET['token'];
        try {
            $model = new ResetPasswordForm($token);
            //$model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            $this->layout = 'main';
            Yii::$app->session->setFlash('success', 'Nueva contraseña guardada.');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
            'imagen' => $this->imagen,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model) {
            $model = User::findByVerificationToken($token);
            return $this->actionResetPassword($model->password_reset_token);
        }

        return $this->goHome();
    }

    public function actionProbando()
    {
        return $this->render('probando');
    }
}
