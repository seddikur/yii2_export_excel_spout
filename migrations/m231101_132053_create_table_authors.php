<?php

use yii\db\Migration;

/**
 * Создается таблица "Авторы".
 */
class m231101_132053_create_table_authors extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "Авторы"';
        };

        $this->createTable('{{%authors}}', [
            'id' => $this->primaryKey(),
            'surname' => $this->string(100)->notNull()->comment('Фамилия'),
            'name' => $this->string(100)->notNull()->comment('Имя'),

        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%authors}}');

    }
}
