<?php

namespace app\models;

use app\models\db\Task;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;

/**
 * TaskSearch is the helper class to filtrate tasks.
 */
class TaskSearch extends Task
{
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            [['id', 'status', 'type', 'service_class'], 'integer'],
            [['title', 'create_date'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Method set filters by value.
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Task::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => ['attributes' => ['id', 'type', 'title', 'author', 'executor', 'status', 'create_date', 'service_class']],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['type' => $this->type]);
        $query->andFilterWhere(['service_class' => $this->service_class]);
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'create_date', $this->create_date]);

        return $dataProvider;
    }
}