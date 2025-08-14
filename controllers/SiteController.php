<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{



    public function actionIndex($page = 1, $category = '', $keyword = '')
    {
        $perPage = 10; // jumlah berita per halaman

        // Ambil semua sumber berita sesuai kategori
        $hasil = \Yii::$app->newsApi->getTopHeadlines($category);
        $sources =array_slice( $hasil['sources'],0,10) ?? [];

        // Filter pencarian berdasarkan keyword di array
        if (!empty($keyword)) {
            $keywordLower = mb_strtolower($keyword);
            $sources = array_filter($sources, function($item) use ($keywordLower) {
                return (
                    strpos(mb_strtolower($item['name']), $keywordLower) !== false ||
                    strpos(mb_strtolower($item['description']), $keywordLower) !== false ||
                    strpos(mb_strtolower($item['url']), $keywordLower) !== false
                );
            });
        }

        // Hitung total data
        $total = count($sources);

        // Hitung offset
        $offset = ($page - 1) * $perPage;

        // Potong array sesuai halaman
        $pagedData = array_slice($sources, $offset, $perPage);

        // Hitung total halaman
        $totalPages = ceil($total / $perPage);

 
        $categoryStyles = [
            'all_category'   => ['name' =>'' ,'color' => '', 'icon' => ''],
            'business'      => ['name' =>'business' ,'color' => 'primary', 'icon' => 'fa-briefcase'],
            'entertainment' => ['name' =>'entertainment' ,'color' => 'warning', 'icon' => 'fa-film'],
            'general'       => ['name' =>'general' ,'color' => 'secondary', 'icon' => 'fa-globe'],
            'health'        => ['name' =>'health' ,'color' => 'success', 'icon' => 'fa-heartbeat'],
            'science'       => ['name' =>'science' ,'color' => 'info', 'icon' => 'fa-flask'],
            'sports'        => ['name' =>'sports' ,'color' => 'danger', 'icon' => 'fa-football-ball'],
            'technology'    => ['name' =>'technology' ,'color' => 'dark', 'icon' => 'fa-microchip'],
            'politic'       => ['name' =>'politic' ,'color' => 'primary', 'icon' => 'fa-university'],
        ];

        return $this->render('index', [
            'data'        => $pagedData,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'offset'      => $offset,
            'category'    => $category,
            'categoryStyles' => $categoryStyles,
            'keyword'     => $keyword,
            'total'       => $total,
            'from'        => $total ? ($offset + 1) : 0,
            'to'          => $total ? min($offset + $perPage, $total) : 0
        ]);
    }

     

 
 


    public function actionAll()
    {
        $request = Yii::$app->request;
        
        $keyword = $request->get('keyword', '');

        if($keyword==="" or is_null($keyword)){
            $keyword ='android';
        }


        $from = $request->get('from', date('Y-m-d', strtotime('-2 days')));
        $to = $request->get('to', date('Y-m-d'));
        $page = (int)$request->get('page', 1);

        $pageSize = 5; // jumlah berita per halaman
   

       
        $newsApi = Yii::$app->newsApi; 
        $data = $newsApi->getEverything($keyword, $from, $to, $page, $pageSize) ? $newsApi->getEverything($keyword, $from, $to, $page, $pageSize): [];
        
        // var_dump($data );
        if ($request->isAjax) {
             return $this->renderPartial('_list', [
                'articles' => $data['articles'] ?? [],
                'totalResults' => $data['totalResults'] ?? 0,
                'pageSize' => $pageSize,
                'currentPage' => $page
             ]);
        }

        return $this->render('news_all', [
            'keyword' => $keyword,
            'from' => $from,
            'to' => $to,
            'articles' => $data['articles'] ?? [],
            'totalResults' => $data['totalResults'] ?? 0,
            'pageSize' => $pageSize,
            'currentPage' => $page,
            'data' =>$data 
        ]);
    }


 




    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
    public function actionIndex2()
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
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
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
}