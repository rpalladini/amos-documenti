<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

use lispa\amos\documenti\AmosDocumenti;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \lispa\amos\documenti\models\search\DocumentiSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="documenti-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <h2><?= AmosDocumenti::t('amosdocumenti', 'Ordina per') ?>:</h2>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    ?>
    
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderAttribute')->dropDownList($model->getOrderAttributesLabels()) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderType')->dropDownList(
            [
                SORT_ASC => AmosDocumenti::t('amosdocumenti', 'Crescente'),
                SORT_DESC => AmosDocumenti::t('amosdocumenti', 'Decrescente'),
            ]
        )
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosDocumenti::t('amosdocumenti', 'Annulla'),[Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class'=>'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosDocumenti::t('amosdocumenti', 'Ordina'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>