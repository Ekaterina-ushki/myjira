<?php

namespace app\controllers;

use app\models\AddTaskForm;
use app\models\RegistrationForm;
use app\models\ServiceClass;
use app\models\Status;
use app\models\Task;
use app\models\TaskSearch;
use app\models\Type;
use app\models\User;
use app\models\Comment;
use app\services\CommentService;
use app\services\ServiceClassService;
use app\services\StatusService;
use app\services\TaskService;
use app\services\TypeService;
use app\services\UserService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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
                'only' => ['logout','full-task', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout', 'full-task', 'index'],
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

    public function search($params)
    {
        $query = Task::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->create_date
        ]);

        return $dataProvider;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $types = Type::find()->all();
        $users = User::find()->all();
        $statuses = Status::find()->all();
        $serviceClasses = ServiceClass::find()->all();

        $tasks = Task::find()->all();

        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'types' => $types,
            'users' => $users,
            'statuses' => $statuses,
            'tasks' => $tasks,
            'serviceClasses' => $serviceClasses,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionRegistration()
    {
        $model = new RegistrationForm();
        $userService = new UserService();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $userService->addUser($model->login, $model->email, $model->password);

                Yii::$app->session->setFlash('success', 'user signed up');
            }
            return $this->refresh();
        }

        return $this->render('registration', [
            'model' => $model,
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
            return $this->redirect("/site/index");
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

    public function actionUpdateTask() {
        $model = new AddTaskForm();
        $id = Yii::$app->request->get('id');
        $taskService = new TaskService();

        if (!$id || !$taskService->findById($id)) {
            return $this->render('error');
        }

        if ($model->load(Yii::$app->request->post())) {
            $taskService->updateTask($id, $model->type, $model->title, $model->description,$model->status, $model->executor, $model->serviceClass);

            Yii::$app->session->setFlash('success', 'task updated');
            return $this->refresh();
        }

        $types = Type::find()->all();
        $users = User::find()->all();
        $statuses = Status::find()->all();
        $serviceClasses = ServiceClass::find()->all();

        $tasks = Task::find()->all();

        return $this->render('update-task', [
            'model' => $model,
            'types' => $types,
            'users' => $users,
            'statuses' => $statuses,
            'tasks' => $tasks,
            'serviceClasses' => $serviceClasses
        ]);

    }

    public function actionFullTask()
    {
        $id = Yii::$app->request->get('id');
        $taskService = new TaskService();

        if (!$id || !$taskService->findById($id)) {
            return $this->render('error');
        }

        $userService = new UserService();
        $typeService = new TypeService();
        $statusService = new StatusService();
        $serviceClassService = new ServiceClassService();
        $commentService = new CommentService();

        $task = $taskService->findById($id);
        $author = $userService->findById($task->author_id);
        $executor = $userService->findById($task->executor_id);
        $type = $typeService->findById($task->type);
        $status = $statusService->findById($task->status);
        $serviceClass = $serviceClassService->findById($task->service_class);


        $types = Type::find()->all();
        $users = User::find()->all();
        $statuses = Status::find()->all();
        $serviceClasses = ServiceClass::find()->all();
        $comments = $commentService->findByTaskId($id);

        return $this->render('full-task', [
            'task'=> $task,
            'author'=> $author,
            'executor'=> $executor,
            'type'=> $type,
            'status'=> $status,
            'serviceClass' => $serviceClass,
            'types'=> $types,
            'users'=> $users,
            'statuses'=> $statuses,
            'serviceClasses' => $serviceClasses,
            'comments' => $comments
        ]);
    }

    public function actionAddTask() {
        $model = new AddTaskForm();

        $taskService = new TaskService();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $taskService->addTask($model->type, $model->title, $model->description,
                    $model->status, $model->executor, $model->serviceClass);

                Yii::$app->session->setFlash('success', 'task added');
            }
            return $this->refresh();
        }

        $types = Type::find()->all();
        $users = User::find()->all();
        $statuses = Status::find()->all();
        $serviceClasses = ServiceClass::find()->all();


        return $this->render('add-task', [
            'model' => $model,
            'types' => $types,
            'users' => $users,
            'statuses' => $statuses,
            'serviceClasses' => $serviceClasses
        ]);
    }

    public function actionDeleteTask() {
        $model = new AddTaskForm();

        $id = Yii::$app->request->get('id');
        $taskService = new TaskService();
        $taskService->deleteTask($id);

        Yii::$app->session->setFlash('success', 'task deleted');

        $types = Type::find()->all();
        $users = User::find()->all();
        $statuses = Status::find()->all();
        $serviceClasses = ServiceClass::find()->all();

        $tasks = Task::find()->all();

        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'model' => $model,
            'types' => $types,
            'users' => $users,
            'statuses' => $statuses,
            'tasks' => $tasks,
            'serviceClasses' => $serviceClasses,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }


    public function actionAddComment()
    {
        $model = new AddTaskForm();

        $id = Yii::$app->request->get('id');
        $text = Yii::$app->request->get('text');

        $commentService = new CommentService();
        $taskService = new TaskService();

        if (!$id || !$taskService->findById($id)) {
            return $this->render('error');
        }
        $commentService->addComment($id, $text);

        Yii::$app->session->setFlash('success', 'comment added');

        $userService = new UserService();
        $typeService = new TypeService();
        $statusService = new StatusService();
        $serviceClassService = new ServiceClassService();

        $task = $taskService->findById($id);
        $author = $userService->findById($task->author_id);
        $executor = $userService->findById($task->executor_id);
        $type = $typeService->findById($task->type);
        $status = $statusService->findById($task->status);
        $serviceClass = $serviceClassService->findById($task->service_class);
        $comments = $commentService->findByTaskId($id);

        $types = Type::find()->all();
        $users = User::find()->all();
        $statuses = Status::find()->all();
        $serviceClasses = ServiceClass::find()->all();

        return $this->render('full-task', [
            'model' => $model,
            'task'=> $task,
            'author'=> $author,
            'executor'=> $executor,
            'type'=> $type,
            'status'=> $status,
            'serviceClass' => $serviceClass,
            'types'=> $types,
            'users'=> $users,
            'statuses'=> $statuses,
            'serviceClasses' => $serviceClasses,
            'comments' => $comments
        ]);
    }
}
