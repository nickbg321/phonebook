<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Controller responsible for displaying errors to the user, Yii will automatically redirect catchable errors here.
 *
 * Class ErrorController
 * @package app\controllers
 */
class ErrorController extends Controller
{
    /**
     * @var string
     */
    public $layout = 'error';

    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
