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

use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\DocumentiCategorie $model
 */

$this->title = AmosDocumenti::t('amosdocumenti', $model->titolo);
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', 'Documenti'), 'url' => '/documenti'];
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', 'Categorie documenti'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->titolo, 'url' => ['view', 'id' => $model->id]];
?>
<div class="documenti-categorie-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
