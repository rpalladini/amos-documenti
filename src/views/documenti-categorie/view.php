<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\views\documenti-categorie
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\DocumentiCategorie $model
 */

$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', 'Documenti'), 'url' => '/documenti'];
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', 'Categorie documenti'), 'url' => ['index']];
?>
<div class="documenti-categorie-view">
    <div class="row">
        <div class="col-xs-12">
            <div class="body">
                <section class="section-data">
                    <h2>
                        <?= AmosIcons::show('rss'); ?>
                        <?= AmosDocumenti::tHtml('amosdocumenti', 'Categorie Documenti') ?>
                    </h2>
                    <dl>
                        <dt><?= $model->getAttributeLabel('titolo'); ?></dt>
                        <dd><?= $model->titolo; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('sottotitolo'); ?></dt>
                        <dd><?= $model->sottotitolo; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('descrizione_breve'); ?></dt>
                        <dd><?= $model->descrizione_breve; ?></dd>
                    </dl>
                    <dl>
                        <dt><?= $model->getAttributeLabel('descrizione'); ?></dt>
                        <dd><?= $model->descrizione; ?></dd>
                    </dl>
                </section>
            </div>
        </div>
    </div>
</div>
