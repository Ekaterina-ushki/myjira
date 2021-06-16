<?php

namespace app\controllers;

use app\models\forms\AddTaskForm;
use app\models\db\ServiceClass;
use app\models\db\Status;
use app\models\db\Task;
use app\models\TaskSearch;
use app\models\db\Type;
use app\models\db\User;
use app\services\CommentService;
use app\services\ServiceClassService;
use app\services\StatusService;
use app\services\TaskService;
use app\services\TypeService;
use app\services\UserService;
use Yii;
use yii\web\Controller;

class TaskController extends Controller
{
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
     * Displays page with tasks list.
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

    /**
     * Displays task full information page.
     *
     * @return string
     */
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

    /**
     * Action add task.
     *
     * @return string
     */
    public function actionAddTask() {
        $model = new AddTaskForm();

        $taskService = new TaskService();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $taskService->addTask($model->type, $model->title, $model->description,
                    $model->status, $model->executor, $model->serviceClass);

                Yii::$app->session->setFlash('success', 'task added');
                return $this->redirect(['task/index']);
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

    /**
     * Action update task by task id.
     *
     * @return string
     */
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
            return $this->redirect(['task/full-task?id=' . $id]);
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

    /**
     * Action delete task by task id.
     *
     * @return string
     */
    public function actionDeleteTask() {
        $id = Yii::$app->request->get('id');
        $taskService = new TaskService();
        $taskService->deleteTask($id);

        Yii::$app->session->setFlash('success', 'task deleted');
        return $this->redirect(['task/index']);
    }

    /**
     * Action add comment to task by task id.
     *
     * @return string
     */
    public function actionAddComment()
    {
        $id = Yii::$app->request->get('id');
        $text = Yii::$app->request->get('text');

        $commentService = new CommentService();
        $taskService = new TaskService();

        if (!$id || !$taskService->findById($id)) {
            return $this->render('error');
        }
        $commentService->addComment($id, $text);

        Yii::$app->session->setFlash('success', 'comment added');

        return $this->redirect(["task/full-task?id=" . $id]);
    }
}