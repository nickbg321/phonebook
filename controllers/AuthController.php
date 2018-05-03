<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\form\LoginForm;
use yii\web\Response;

/**
 * Controller responsible for handling user authentication action like login and logout.
 *
 * Class AuthController
 * @package app\controllers
 */
class AuthController extends Controller
{
    /**
     * @var string
     */
    public $layout = 'login';

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Action for handling user authentication and login.
     * Displays the login form and triggers the login process.
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        /** @var LoginForm $loginForm */
        $loginForm = Yii::$container->get(LoginForm::class);

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goBack();
        }

        $loginForm->password = '';

        return $this->render('login', [
            'model' => $loginForm,
        ]);
    }

    /**
     * Action for handling user logout.
     *
     * @return \yii\web\Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
