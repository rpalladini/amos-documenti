<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\views\documenti-allegati
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

use lispa\amos\core\forms\CloseSaveButtonWidget;
use lispa\amos\core\forms\ActiveForm;
use kartik\file\FileInput;
use yii\bootstrap\Tabs;
use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\DocumentiAllegati $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="documenti-allegati-form col-xs-12">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php $this->beginBlock('dettagli'); ?>

    <div class="col-lg-12 col-sm-12">

        <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-12 col-sm-12">

        <?= $form->field($model, 'descrizione')->textarea(['rows' => 6]) ?>
    </div>
    <div class="col-lg-12 col-sm-12">
        <?=
        $form->field($model, 'file')->widget(FileInput::classname(), [
            'pluginOptions' => [
                'showPreview' => false,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
            ],
            'options' => [
                'name' => 'FilemanagerMediafile[file]',
                'disabled' => FALSE],
        ]);
        ?>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock('dettagli'); ?>

    <?php
    $itemsTab[] = [
        'label' => AmosDocumenti::tHtml('amosdocumenti', 'dettagli '),
        'content' => $this->blocks['dettagli'],
    ];
    ?>

    <?=
    Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>
    <?php
    $config = [
        'model' => $model
    ];
    echo CloseSaveButtonWidget::widget($config);
    ?>
    <?php ActiveForm::end(); ?>
</div>
