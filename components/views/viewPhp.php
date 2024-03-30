<?php
use yii\bootstrap5\Html;

/* @var $widget \app\components\ExportPhp */

?>
<?php if ($widget->xls): ?>
    <div class="<?= $widget->blockClass ?>" style="<?= $widget->blockStyle ?>">
        <?php
        echo Html::beginForm(['/exportPhp/excel'], 'post');
        echo Html::hiddenInput('model', $widget->model);
        echo Html::hiddenInput('queryParams', $widget->queryParams);
        echo Html::hiddenInput('getAll', $widget->getAll);
        echo Html::hiddenInput('title', $widget->title);
        echo Html::submitButton($widget->xlsButtonName,
            ['class' => $widget->buttonClass,]
        );
        echo Html::endForm();
        ?>
    </div>
<?php endif; ?>
<?php if ($widget->csv): ?>
    <div class="<?= $widget->blockClass ?>" style="<?= $widget->blockStyle ?>">
        <?php
        echo Html::beginForm(['/exportPhp/csv'], 'post');
        echo Html::hiddenInput('model', $widget->model);
        echo Html::hiddenInput('queryParams', $widget->queryParams);
        echo Html::hiddenInput('getAll', $widget->getAll);
        echo Html::hiddenInput('title', $widget->title);
        echo Html::submitButton($widget->csvButtonName,
            ['class' => $widget->buttonClass,]
        );
        echo Html::endForm();
        ?>
    </div>
<?php endif; ?>
<?php if ($widget->word): ?>
    <div class="<?= $widget->blockClass ?>" style="<?= $widget->blockStyle ?>">
        <?php
        echo Html::beginForm(['/exportPhp/word'], 'post');
        echo Html::hiddenInput('model', $widget->model);
        echo Html::hiddenInput('queryParams', $widget->queryParams);
        echo Html::hiddenInput('getAll', $widget->getAll);
        echo Html::hiddenInput('title', $widget->title);
        echo Html::submitButton($widget->wordButtonName,
            ['class' => $widget->buttonClass,]
        );
        echo Html::endForm();
        ?>
    </div>
<?php endif; ?>
<?php if ($widget->html): ?>
    <div class="<?= $widget->blockClass ?>" style="<?= $widget->blockStyle ?>">
        <?php
        echo Html::beginForm(['/exportPhp/html'], 'post');
        echo Html::hiddenInput('model', $widget->model);
        echo Html::hiddenInput('queryParams', $widget->queryParams);
        echo Html::hiddenInput('getAll', $widget->getAll);
        echo Html::hiddenInput('title', $widget->title);
        echo Html::submitButton($widget->htmlButtonName,
            ['class' => $widget->buttonClass,]
        );
        echo Html::endForm();
        ?>
    </div>
<?php endif; ?>
<?php if ($widget->pdf): ?>
    <div class="<?= $widget->blockClass ?>" style="<?= $widget->blockStyle ?>">
        <?php
        echo Html::beginForm(['/exportPhp/pdf'], 'post');
        echo Html::hiddenInput('model', $widget->model);
        echo Html::hiddenInput('queryParams', $widget->queryParams);
        echo Html::hiddenInput('getAll', $widget->getAll);
        echo Html::hiddenInput('title', $widget->title);
        echo Html::submitButton($widget->pdfButtonName,
            ['class' => $widget->buttonClass,]
        );
        echo Html::endForm();
        ?>
    </div>
<?php endif; ?>