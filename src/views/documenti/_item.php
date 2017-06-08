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

use lispa\amos\core\forms\ContextMenuWidget;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\core\forms\ItemAndCardHeaderWidget;
use lispa\amos\documenti\assets\ModuleDocumentiAsset;

ModuleDocumentiAsset::register($this);
?>

<div class="listview-container documents">
    <div class="post-horizonatal">
         <?php
            $creatoreDocumenti = $model->getCreatedUserProfile()->one();
            $dataPubblicazione = Yii::$app->getFormatter()->asDatetime($model->created_at);
            $nomeCreatoreDocumenti = AmosDocumenti::tHtml('amosdocumenti', 'Utente Cancellato');
            ?>
            <?= ItemAndCardHeaderWidget::widget([
                'model' => $model,
                'publicationDateField' => 'created_at',
                'hideInteractionMenu' => true   // TODO rimuovere questa opzione dal widget quando saranno sviluppate le funzionalità
            ]);

            $document = $model->getDocumentMainFile()->one();

            if ($document):
                $classContainer = 'col-sm-7 col-xs-12';
            else:
                $classContainer = 'col-xs-12';
            endif;
            ?>
        <div class="<?=$classContainer?> nop">
            <div class="post-content col-xs-12 nop">
                <div class="post-title col-xs-10">
                    <a href="/documenti/documenti/view?id=<?= $model->id ?>">
                        <h2><?= $model->titolo ?></h2>
                    </a>
                </div>
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                    'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                    'mainDivClasses' => 'col-xs-1 nop'
                ]) ?>
                <div class="clearfix"></div>
                <div class="row nom post-wrap">
                    <div class="post-text col-xs-12">
                        <p>
                            <?= $model->descrizione_breve ?>
                            <a class="underline" href=/documenti/documenti/view?id=<?= $model->id ?>><?= AmosDocumenti::tHtml('amosdocumenti', 'Leggi tutto') ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar col-sm-5 col-xs-12">
            <div class="container-sidebar">
                <?php
                if ($document) : ?>
                    <div class="box">
                        <?= '<span class="icon">' . AmosIcons::show('download-general',['class' => 'am-4'], 'dash') . '</span><p class="title">' .$document->name .'</p>'; ?>
                    </div>
                    <div class="footer_sidebar col-xs-12 nop">
                        <?= Html::a('Scarica File', ['/attachments/file/download/', 'id' => $document->id,'hash' => $document->hash], [
                            'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                            'class' => 'bk-btnImport pull-right btn btn-amministration-primary',
                        ]); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="post-footer col-xs-12 nop">
            <div class="post-info">
                <?= \lispa\amos\core\forms\PublishedByWidget::widget([
                    'model' => $model,
                ]) ?>
                <p><strong>Categoria:</strong> <?= $model->documentiCategorie->titolo ?></p>
                <p><strong>Stato:</strong> <?= $model->getWorkflowStatus()->getLabel() ?></p>
                <p><strong><?= ($model->primo_piano) ? AmosDocumenti::tHtml('amosdocumenti', 'Pubblicato in prima pagina') : '' ?></strong></p>
            </div>

            <?php
            //$statsToolbar = $model->getStatsToolbar();
            $visible = isset($statsToolbar) ? $statsToolbar : false;
            if($visible) {
                echo \lispa\amos\core\views\toolbars\StatsToolbar::widget([
                    'model' => $model,
                ]);
            }
            ?>
        </div>
    </div>
</div>