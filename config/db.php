<?php

return [

    'class' => yii\db\Connection::class,
    'dsn' => 'mysql:host=yii2_sql;dbname=yii',
    'username' => 'yii',
    'password' => 'yii',
    'charset' => 'utf8',
//    'enableSchemaCache' => true,
//    'enableQueryCache' => false,
//    'schemaCacheDuration' => 3600,
//    'schemaCache' => 'cache',


//    'class' => 'yii\db\Connection',
//    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
//    'username' => 'root',
//    'password' => '',
//    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
