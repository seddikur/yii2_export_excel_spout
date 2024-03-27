<?php

use yii\db\Migration;

/**
 * Создается таблица "Книги".
 */
class m231101_132104_create_table_books extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "Книги"';
        };

        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull()->comment('Название'),
            'author_id' => $this->integer()->notNull()->comment('Автор'),
            'publish_year' => $this->integer(11)->notNull()->comment('Год'),
            'pages' => $this->integer()->notNull()->comment('Кол-во страниц')
        ], $tableOptions);

        $this->createIndex('author_id', 'books', 'author_id');

        $this->addForeignKey(
            'fk_books_author_id',
            'books',
            'author_id',
            'authors',
            'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_books_author_id', 'books');

        $this->dropIndex('author_id', 'books');

        $this->dropTable('books');
    }

}
