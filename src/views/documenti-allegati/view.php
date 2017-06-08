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

use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\DocumentiAllegati $model
 */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', Yii::$app->session->get('previousTitle')), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = ['label' => lispa\amos\documenti\models\Documenti::findOne(['id' => $model->documenti_id])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documenti-allegati-view">
    <div class="row">
        <div class="col-xs-12 right-column">
            <div class="body">
                <section class="section-data">
                    <h2>
                        <?= AmosIcons::show('account'); ?>
                        <?= AmosDocumenti::tHtml('amosdocumenti', 'Dettagli Allegati') ?>
                    </h2>
                    <dl>
                        <dt><?= $model->getAttributeLabel('titolo'); ?></dt>
                        <dd><?= $model->titolo; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('descrizione'); ?></dt>
                        <dd><?= (!$model->descrizione ? '--' : $model->descrizione); ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('created_at'); ?></dt>
                        <dd><?= $model->created_at; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('updated_at'); ?></dt>
                        <dd><?= $model->updated_at; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('deleted_at'); ?></dt>
                        <dd><?= (!$model->deleted_at ? '--' : $model->deleted_at); ?></dd>
                    </dl>
                </section>
            </div>

        </div>
    </div>
</div>
<?= \lispa\amos\core\forms\CloseButtonWidget::widget([])?>
