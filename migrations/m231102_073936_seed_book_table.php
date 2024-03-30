<?php

use yii\db\Migration;

/**
 * Class m231102_073936_seed_book_table
 */
class m231102_073936_seed_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        {
            $faker = \Faker\Factory::create();
            for ($i = 0; $i < 5050; $i++) {
                $this->insert(
                    'books',
                    [
                        'title'         => $faker->catchPhrase,
                        'publish_year' => (int)$faker->year,
                        'author_id'  => (int)rand(1, 20),
                        //'created_at' => (new \yii\db\Expression('NOW()')),
                        'pages' =>(int)rand(1, 300),
                    ]
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231102_073936_seed_book_table cannot be reverted.\n";

        return false;
    }
    */
}
