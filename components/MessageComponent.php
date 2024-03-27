<?php

namespace app\components;

use yii\base\Component;
use yii\helpers\Html;

/**
 * Регистрируем компонент в файле config/web.php
 * 'message' => [
 * 'class' => 'app\components\MessageComponent',
 * ],
 *
 * Используем Yii::$app->message->display('Привет мир');
 */
class MessageComponent extends Component
{ // объявляем класс

    public $content;

    public function init()
    { // функция инициализации. Если данные не будут переданы в компонент, то он выведет текст "Текст по умолчанию"
        parent::init();
        $this->content = 'Текст по умолчанию';
    }

    public function display($content = null)
    { // функция отображения данных
        if ($content != null) { //проверка строки на пустоту
            $this->content = $content;
        }
        echo Html::encode($this->content); // вывод данных
    }

}