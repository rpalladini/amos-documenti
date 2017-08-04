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

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\Documenti $model
 */

use lispa\amos\core\forms\ContextMenuWidget;
use lispa\amos\core\forms\ItemAndCardHeaderWidget;
use lispa\amos\core\forms\ShowUserTagsWidget;
use lispa\amos\core\forms\Tabs;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\assets\ModuleDocumentiAsset;

ModuleDocumentiAsset::register($this);

$this->title = $model->titolo;
$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
if (isset($ruolo['ADMIN'])) {
    $url = ['index'];
}
/** @var \lispa\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', Yii::$app->session->get('previousTitle')), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

// Tab ids
$idTabCard = 'tab-card';
$idClassifications = 'tab-classifications';
$idTabAttachments = 'tab-attachments';

?>
<div class="documenti-view post-horizonatal documents col-xs-12 nop">
    <?php $this->beginBlock('card'); ?>

    <?php
    $creatoreDocumenti = $model->getCreatedUserProfile()->one();
    $nomeCreatoreDocumenti = $creatoreDocumenti->nome . " " . $creatoreDocumenti->cognome;
    $dataPubblicazione = Yii::$app->getFormatter()->asDatetime($model->created_at);
    $avatarCreatore = $creatoreDocumenti->avatar_id;
    ?>
    <?= ItemAndCardHeaderWidget::widget([
        'model' => $model,
        'publicationDateField' => 'created_at',
    ]) ?>
    <?php
    $document = $model->getDocumentMainFile()->one();
    if ($document):
        $classContainer = 'col-sm-7 col-xs-12';
    else:
        $classContainer = 'col-xs-12';
    endif;
    ?>
    <div class="<?= $classContainer ?> nop">
        <div class="post-content col-xs-12 nop">
            <div class="post-title col-xs-10">
                <h2><?= $model->titolo ?></h2>
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
                    <h3 class="subtitle"><?= $model->sottotitolo ?></h3>
                    <?= $model->descrizione ?>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar col-sm-5 col-xs-12">
        <div class="container-sidebar">
            <?php

            if ($document) : ?>
                <div class="box">
                    <?= '<span class="icon">' . AmosIcons::show('download-general', ['class' => 'am-4'], 'dash') . '</span><p class="title">' . $document->name . '.' . $document->type . '</p>'; ?>
                </div>
                <div class="box post-info">
                    <?= \lispa\amos\core\forms\PublishedByWidget::widget([
                        'model' => $model,
                    ]) ?>
                    <p><strong><?= AmosDocumenti::tHtml('amosdocumenti', 'Categoria') ?>:</strong> <?= $model->documentiCategorie->titolo ?></p>
                    <p><strong><?= AmosDocumenti::tHtml('amosdocumenti', 'Stato') ?>:</strong> <?= $model->getWorkflowStatus()->getLabel() ?></p>
                    <p><strong><?= ($model->primo_piano) ? AmosDocumenti::tHtml('amosdocumenti', 'Pubblicato in prima pagina') : '' ?></strong></p>
                </div>

                <div class="footer_sidebar col-xs-12 nop">
                    <?= Html::a('Scarica File', ['/attachments/file/download/', 'id' => $document->id,'hash' => $document->hash], [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                        'class' => 'bk-btnImport pull-right btn btn-amministration-primary',
                    ]); ?>

                    <?php
                    $statsToolbar = $model->getStatsToolbar();
                    $visible = isset($statsToolbar) ? $statsToolbar : false;
                    if($visible) {
                        echo \lispa\amos\core\views\toolbars\StatsToolbar::widget([
                            'model' => $model,
                            'onClick' => true
                        ]);
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>



    <?php $this->endBlock(); ?>

    <?php
    $itemsTab[] = [
        'label' => AmosDocumenti::tHtml('amosdocumenti', 'Scheda'),
        'content' => $this->blocks['card'],
        'options' => ['id' => $idTabCard],
    ];
    ?>

    <?php if (Yii::$app->getModule('tag')): ?>
        <?php $this->beginBlock($idClassifications); ?>
        <div class="body">
            <?= ShowUserTagsWidget::widget([
                'userProfile' => $model->id,
                'className' => $model->className()
            ]);
            ?>
        </div>
        <?php $this->endBlock(); ?>
        <?php
        $itemsTab[] = [
            'label' => AmosDocumenti::tHtml('amosdocumenti', 'Tag'),
            'content' => $this->blocks[$idClassifications],
            'options' => ['id' => $idClassifications],
        ];
        ?>
    <?php endif; ?>

    <?php $this->beginBlock('attachments'); ?>
    <div class="allegati col-xs-12 nop">
        <?php if ($model->getDocumentAttachments()->count() == 0): ?>
            <p><h4><?= AmosDocumenti::tHtml('amosdocumenti', 'Nessun allegato presente') ?></h4></p>
        <?php else: ?>
            <p>
            <h3><?= AmosDocumenti::tHtml('amosdocumenti', 'Allegati') ?></h3>
            </p>
            <?= \lispa\amos\attachments\components\AttachmentsTableWithPreview::widget([
                'model' => $model,
                'attribute' => 'documentAttachments',
                'viewDeleteBtn' => false
            ]) ?>
        <?php endif; ?>
    </div>
    <?php $this->endBlock(); ?>

    <?php
    $itemsTab[] = [
        'label' => AmosDocumenti::tHtml('amosdocumenti', 'Allegati'),
        'content' => $this->blocks['attachments'],
        'options' => ['id' => $idTabAttachments],
    ];
    ?>

    <?= Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>
</div>