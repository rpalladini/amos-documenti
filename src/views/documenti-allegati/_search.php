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

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\search\DocumentiAllegatiSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="documenti-allegati-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= AmosDocumenti::tHtml('Cerca per') ?>:</h2></div>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'titolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'descrizione') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'filemanager_mediafile_id') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'documenti_id') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'created_at') ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosDocumenti::tHtml('amosdocumenti', 'Resetta'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosDocumenti::tHtml('amosdocumenti', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>

</div>
