<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_author}}`.
 */
class m231102_072544_create_book_author_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%book_author}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer(11)->notNull(),
            'author_id' => $this->integer(11)->notNull(),
            'status' => $this->integer(2)
        ]);

        $this->addForeignKey(
            'fk-book_author-book_id',
            'book_author',
            'book_id',
            'books',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-book_author-author_id',
            'book_author',
            'author_id',
            'authors',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book_author}}');
    }
}
