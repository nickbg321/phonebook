<?php

namespace app\controllers;

use app\models\data\ContactData;
use app\models\form\ContactForm;
use app\models\data\repository\ContactRepository;
use app\models\search\ContactSearch;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Controller for handling the app's main functionality.
 *
 * Class IndexController
 * @package app\controllers
 */
class IndexController extends Controller
{
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * IndexController constructor.
     * @param string $id
     * @param Module $module
     * @param ContactRepository $contactRepository
     */
    public function __construct(string $id, Module $module, ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;

        parent::__construct($id, $module);
    }

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
                        'actions' => ['index', 'edit', 'delete', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Action for displaying the contacts list.
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionIndex(): string
    {
        $searchModel = Yii::$container->get(ContactSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('contacts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Action for handling the contact creation process.
     * Displays the contact creation modal window and triggers the saving process.
     *
     * @return array|string|Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionCreate()
    {
        /** @var ContactForm $contactForm */
        $contactForm = Yii::$container->get(ContactForm::class);

        if (Yii::$app->request->isAjax && $contactForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($contactForm);
        }

        if ($contactForm->load(Yii::$app->request->post())) {
            $errors = ActiveForm::validate($contactForm);

            if ($errors) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $errors;
            }

            try {
                $contactForm->save();
                Yii::$app->session->setFlash('success', 'Contact successfully created.');
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Failed saving contact.');
            }

            return $this->redirect('/');
        } else {
            return $this->renderAjax('manage', [
                'contactForm' => $contactForm,
            ]);
        }
    }

    /**
     * Action for handling the contact editing process.
     * Displays the contact editing modal window and triggers the saving process.
     *
     * @param int $contactId
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionEdit(int $contactId)
    {
        /** @var ContactForm $contactForm */
        $contactForm = Yii::$container->get(ContactForm::class, [$this->getContactData($contactId)]);

        if (Yii::$app->request->isAjax && $contactForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($contactForm);
        }

        if ($contactForm->load(Yii::$app->request->post())) {
            $errors = ActiveForm::validate($contactForm);

            if ($errors) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $errors;
            }

            try {
                $contactForm->save();
                Yii::$app->session->setFlash('success', 'Contact successfully edited.');
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Failed saving contact.');
            }

            return $this->redirect('/');
        } else {
            return $this->renderAjax('manage', [
                'contactForm' => $contactForm,
            ]);
        }
    }

    /**
     * Action for handling the contact deletion process.
     * Deletes the contact and returns the user to the referer address.
     *
     * @param int $contactId
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $contactId): Response
    {
        $contactData = $this->getContactData($contactId);

        if ($contactData->delete()) {
            Yii::$app->session->setFlash('success', 'Contact ID ' . Html::encode($contactId) .
                ' deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed deleting contact ID ' . Html::encode($contactId) . '.');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Retrieves contact ActiveRecord by a given ID or throws an exception if a contact with this ID is not found.
     *
     * @param int $contactId
     * @return ContactData
     * @throws NotFoundHttpException
     */
    protected function getContactData(int $contactId): ContactData
    {
        /** @var ContactData $contactData */
        $contactData = $this->contactRepository->getById($contactId);

        if (!$contactData) {
            throw new NotFoundHttpException('Contact ID ' . Html::encode($contactId) . ' not found.');
        }

        return $contactData;
    }
}
