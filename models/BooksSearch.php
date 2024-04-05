<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Books;

/**
 * BooksSearch represents the model behind the search form of `app\models\Books`.
 */
class BooksSearch extends Books
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'publish_year', 'pages'], 'integer'],
            [['title'], 'safe'],
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
        $query = Books::find();

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
            'author_id' => $this->author_id,
            'publish_year' => $this->publish_year,
            'pages' => $this->pages,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

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
            'author_id' ,
            'author_id' => function ($model) {
                /* @var $model Authors */
                return $model->author->surname;
            },
            'title',
//            'title' => function ($model) {
//                /* @var $model Books */
//                if (isset($model->books->name)) {
//                    return $model->books->name;
//                }
//                return false;
//            },
        ];

        return $title;
    }
}
