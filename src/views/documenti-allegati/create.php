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
$this->title = AmosDocumenti::t('amosdocumenti', 'Inserisci documento');
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', Yii::$app->session->get('previousTitle')), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = ['label' => lispa\amos\documenti\models\Documenti::findOne(['id' => $idDocumenti]), 'url' => ['/documenti/documenti/update', 'id' => $idDocumenti, '#' => 'allegati']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documenti-allegati-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'idDocumenti' => $idDocumenti
    ])
    ?>

</div>

