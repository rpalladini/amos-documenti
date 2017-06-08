<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\views\documenti
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

use lispa\amos\core\forms\ActiveForm;
use lispa\amos\core\forms\CloseSaveButtonWidget;
use lispa\amos\core\forms\Tabs;
use lispa\amos\core\forms\WorkflowStateWidget;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\core\views\AmosGridView;
use lispa\amos\core\views\assets\AmosCoreAsset;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\assets\ModuleDocumentiAsset;
use lispa\amos\documenti\models\Documenti;
use kartik\alert\Alert;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

AmosCoreAsset::register($this);
ModuleDocumentiAsset::register($this);
/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\Documenti $model
 * @var yii\widgets\ActiveForm $form
 */

?>

    <?php 
    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] // important
    ]);
    $customView = Yii::$app->getViewPath() . '/imageField.php';
    ?>

    <?= \lispa\amos\core\forms\WorkflowTransitionWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => Documenti::DOCUMENTI_WORKFLOW,
        'classDivIcon' => 'pull-left',
        'classDivMessage' => 'pull-left message',
        'viewWidgetOnNewRecord' => true
    ]); ?>
<div class="documenti-form col-xs-12">

    <?php $this->beginBlock('dettagli'); ?>

    <div class="row">
        <div class="col-lg-8 col-sm-8">
            <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'sottotitolo')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'descrizione_breve')->textarea(['maxlength' => true, 'rows' => 3]) ?>
        </div>

        <div class="col-lg-4 col-sm-4">

            <div class="col-lg-12 col-sm-12 pull-left">
                <?=
                $form->field($model,
                    'documentMainFile')->widget(\lispa\amos\attachments\components\AttachmentsInput::classname(), [
                    'options' => [
                        'multiple' => FALSE,
                        'accept' => ".csv, .pdf, .txt, .doc, .docx, .xls, .xlsx, .rtf",
                    ],
                    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                        'maxFileCount' => 1,
                        'showRemove' => false,// Client max files,
                        'indicatorNew' => false,
                        'allowedPreviewTypes' => false,
                        'previewFileIconSettings' => false,
                        'overwriteInitial' => false,
                        'layoutTemplates' => false
                    ]
                ])->label(AmosDocumenti::t('amosdocumenti', 'Documento principale'))
                ?>

                <?php
                if (!empty($documento)):
                    ?>
                    <?= $documento->filename ?>
                    <?= Html::a(AmosIcons::show('download', ['class' => 'btn btn-tool-secondary']), ['/documenti/documenti/download-documento-principale', 'id' => $model->id], [
                    'title' => 'Download file',
                    'class' => 'bk-btnImport'
                ]); ?>
                    <?php
                endif;
                ?>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?php
            //            echo $form->field($model, 'descrizione')->widget(CKEditor::className(), [
            //                'options' => ['rows' => 6],
            //                'preset' => 'standard',
            //            ])
            ?>
            <?= $form->field($model, 'descrizione')->widget(\yii\redactor\widgets\Redactor::className()) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-sm-4">
            <?=
            $form->field($model, 'documenti_categorie_id')->widget(Select2::className(), [
                'options' => ['placeholder' => AmosDocumenti::t('amosdocumenti', 'Digita il nome della categoria'), 'id' => 'documenti_categorie_id-id', 'disabled' => FALSE],
                'data' => ArrayHelper::map(lispa\amos\documenti\models\DocumentiCategorie::find()->orderBy('titolo')->asArray()->all(), 'id', 'titolo')
            ]);
            ?>
        </div>
        <div class="col-lg-4 col-sm-4">
            <?=
            $form->field($model, 'primo_piano')->dropDownList([
                '0' => 'No',
                '1' => 'Si'
            ], [

                    'prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona...'),
                    'disabled' => false, 'onchange' => '
                    if($(this).val() == 1) $(\'#documenti-in_evidenza\').prop(\'disabled\', false);
                    if($(this).val() == 0) { 
                        $(\'#documenti-in_evidenza\').prop(\'disabled\', true);
                        $(\'#documenti-in_evidenza\').val(0);
                    }
                    ']
            )
            ?>
        </div>
        <div class="col-lg-4 col-sm-4">
            <?=
            $form->field($model, 'in_evidenza')->dropDownList([
                '0' => 'No',
                '1' => 'Si'
            ], ['prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona...'), 'disabled' => ($model->primo_piano == 0)? true: false]
            )
            ?>
        </div>
        <?php /* $form->field($model, 'abilita_pubblicazione')->dropDownList([
          '0' => 'No',
          '1' => 'Si'
          ], ['prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona')]
          ) */
        ?>
    </div>

    <div class="row">
        <div class="col-lg-6 col-sm-6">

            <?=
            $form->field($model, 'data_pubblicazione')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ])
            ?>
        </div>
        <div class="col-lg-6 col-sm-6">

            <?=
            $form->field($model, 'data_rimozione')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ])
            ?>
        </div>
    </div>

    <?php $this->endBlock('dettagli'); ?>

    <?php
    $itemsTab[] = [
        'label' => AmosDocumenti::tHtml('amosdocumenti', 'Dettagli '),
        'content' => $this->blocks['dettagli'],
        'options' => ['id' => 'tab-dettagli'],
    ];
    ?>

    <?php $this->beginBlock('allegati'); ?>
    <?php
    if ($model->isNewRecord) :
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'body' => AmosDocumenti::tHtml('amosdocumenti', 'Prima di poter inserire degli allegati &egrave; necessario salvare il documento.'),
        ]);
    else :

       echo $form->field($model,
            'documentAttachments')->widget(\lispa\amos\attachments\components\AttachmentsInput::classname(), [
            'options' => [ // Options of the Kartik's FileInput widget
                'multiple' => true, // If you want to allow multiple upload, default to false
            ],
            'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                'maxFileCount' => 100 ,// Client max files,
                'showPreview' => false
            ]
        ])->label(AmosDocumenti::t('amosdocumenti', 'Allegati')) ?>
        <?= \lispa\amos\attachments\components\AttachmentsTableWithPreview::widget([
            'model' => $model,
            'attribute' => 'documentAttachments'
        ]);
    endif;
    ?>

    <div class="clearfix"></div>
    <?php $this->endBlock('allegati'); ?>

    <?php
    $itemsTab[] = [
        'label' => AmosDocumenti::tHtml('amosdocumenti', 'Allegati'),
        'content' => $this->blocks['allegati'],
        'options' => ['id' => 'allegati'],
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
    <div class="col-xs-12 note_asterisk nop">
        <!-- TODO decommentare la linea sotto quando viene effetuata la traduzuione del modulo, inserire nelle traduzioni i valori in italiano e poi ri muovere la riga in italiano -->
        <!--p><!--?= AmosDocumenti::tHtml('amosdocumenti', 'The fields marked with ')?><span class="red">*</span><!--?= AmosDocumenti::tHtml('amosdocumenti', ' are required')?></p-->
        <p><?= AmosDocumenti::tHtml('amosdocumenti', 'I campi contrassegnati con ')?><span class="red">*</span><?= AmosDocumenti::tHtml('amosdocumenti', ' sono obbligatori')?></p>
    </div>
    <?php
    $config = [
        'model' => $model
    ];
    echo CloseSaveButtonWidget::widget($config);
    ?>

    <?php ActiveForm::end(); ?>
</div>

