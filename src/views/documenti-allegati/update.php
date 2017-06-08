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

use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\DocumentiAllegati $model
 */

$this->title = AmosDocumenti::t('amosdocumenti', 'Aggiorna {modelClass}', [
    'modelClass' => 'allegati',
]);
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', 'Allegati'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'filemanager_mediafile_id' => $model->filemanager_mediafile_id, 'documenti_id' => $model->documenti_id]];
$this->params['breadcrumbs'][] = AmosDocumenti::t('amosdocumenti', 'Aggiorna');
?>

<div class="documenti-allegati-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

