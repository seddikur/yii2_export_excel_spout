<?php

namespace app\models;

use app\models\Authors;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AuthorsSearch represents the model behind the search form of `app\models\Authors`.
 */
class AuthorsSearch extends Authors
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['surname', 'name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Authors::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Указываются свойства, которые нужно выводить в файлы
     * @return array
     */
    public function exportFields()
    {
        $title  = [
            'id',
//            'id' => function ($model) {
//                /* @var $model Authors */
//                return $model->id;
//            },
            'surname',
//            'title' => function ($model) {
//                /* @var $model Books */
//                if (isset($model->books->name)) {
//                    return $model->books->name;
//                }
//                return false;
//            },
            'name',
        ];

        return $title;
    }
}
