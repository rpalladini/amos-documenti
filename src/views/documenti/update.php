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

use yii\helpers\Url;
use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\Documenti $model
 */
/** @var \lispa\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$this->title = AmosDocumenti::t('amosdocumenti', $model->titolo);
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', Yii::$app->session->get('previousTitle')), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = AmosDocumenti::t('amosdocumenti', 'Aggiorna');
?>

<div class="documenti-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
