<?php

namespace app\controllers;

use app\models\Document;
use app\models\File;
use app\models\RegisterForm;
use app\models\User;
use app\models\UserSearch;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;

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
                'only' => ['logout'],
                'rules' => [
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
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post()) {
            $spreadsheet = new Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();

            $allData = $dataProvider->query->all();
            foreach ($allData as $index => $data) {
                $file = File::find()->where('user_id=:id',[':id'=> $data->id])->one();
                $sheet->setCellValueByColumnAndRow(1, $index + 1, $data->username);
                $sheet->setCellValueByColumnAndRow(2, $index + 1, $data->organizationName);
                $sheet->setCellValueByColumnAndRow(3, $index + 1, $file ? $file->path : '');
                $documents = Document::find()->where('user_id=:id',[':id'=> $data->id])->all();
                $documentsList = '';
                foreach ($documents as $document) {
                    $documentsList .= 'http://localhost:8000/uploads/files/'.$document->path;
                    $documentsList .= ' ';
                }
                $sheet->setCellValueByColumnAndRow(4, $index + 1, $documentsList ? $documentsList : '');
            }
            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode('export.xlsx').'"');
            $writer->save('php://output');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    //test function to add admin
//    public function actionAddAdmin() {
//        $model = User::find()->where(['username' => 'admin'])->one();
//        if (empty($model)) {
//            $user = new User();
//            $user->username = 'admin';
//            $user->setPassword('admin');
//            $user->generateAuthKey();
//            if ($user->save()) {
//                $this->goHome();
//            }
//        }
//    }

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

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->register()) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                $model->documentFiles = UploadedFile::getInstances($model, 'documentFiles');
                if ($model->upload(time(), $user->id)) {
                    Yii::$app->session->setFlash('success','OK');
                }
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            } else {
                Yii::$app->session->setFlash('warning','Something went wrong');
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('contact', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
}
