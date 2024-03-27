<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title Название
 * @property int $author_id Автор
 * @property int $publish_year Год
 * @property int $pages Кол-во страниц
 *
 * @property Authors $author
 * @property BookAuthor[] $bookAuthors
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author_id', 'publish_year', 'pages'], 'required'],
            [['author_id', 'publish_year', 'pages'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Authors::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'author_id' => 'Автор',
            'publish_year' => 'Год',
            'pages' => 'Кол-во страниц',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Authors::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthors()
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }
}
