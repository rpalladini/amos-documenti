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

use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\core\utilities\ModalUtility;
use lispa\amos\core\views\DataProviderView;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var lispa\amos\documenti\models\search\DocumentiSearch $model
 * @var \lispa\amos\dashboard\models\AmosUserDashboards $currentDashboard
 */

$actionColumnDefault = '{view}{update}{delete}';
$actionColumnToValidate = '{validate}{reject}';
$actionColumn = $actionColumnDefault;
if (Yii::$app->controller->action->id == 'to-validate-documents') {
    $actionColumn = $actionColumnToValidate . $actionColumnDefault;
}

?>
<div class="documenti-index">
    <?php
    echo $this->render('_search', [
        'model' => $model,
        'originAction' => Yii::$app->controller->action->id
    ]);
    echo $this->render('_order', [
        'model' => $model,
        'originAction' => Yii::$app->controller->action->id
    ]);
    
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'titolo',
                'created_by' => [
                    'attribute' => 'createdUserProfile',
                    'label' => AmosDocumenti::t('amosdocumenti', 'Pubblicato Da'),
                ],
                'data_pubblicazione' => [
                    'attribute' => 'data_pubblicazione',
                    'value' => function ($model) {
                        return (is_null($model->data_pubblicazione)) ? 'Subito' : Yii::$app->formatter->asDate($model->data_pubblicazione);
                    }
                ],
                'data_rimozione' => [
                    'attribute' => 'data_rimozione',
                    'value' => function ($model) {
                        return (is_null($model->data_rimozione)) ? 'Mai' : Yii::$app->formatter->asDate($model->data_rimozione);
                    }
                ],
                'status' => [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->hasWorkflowStatus() ? $model->getWorkflowStatus()->getLabel() : '--';
                    }
                ],
                'documenti_categorie_id' => [
                    'attribute' => 'documentiCategorie.titolo',
                    'label' => AmosDocumenti::t('amosdocumenti', 'Categoria'),
                ],
                [
                    'class' => 'lispa\amos\core\views\grid\ActionColumn',
                    'template' => $actionColumn,
                    'buttons' => [
                        'validate' => function ($url, $model) {
                            /** @var Documenti $model */
                            $btn = '';
                            if (Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
                                $btn = ModalUtility::addConfirmRejectWithModal([
                                    'modalId' => 'validate-document-modal-id',
                                    'modalDescriptionText' => AmosDocumenti::t('amosdocumenti', '#VALIDATE_DOCUMENT_MODAL_TEXT'),
                                    'btnText' => AmosIcons::show('check-circle', ['class' => 'btn btn-tool-secondary']),
                                    'btnLink' => Yii::$app->urlManager->createUrl(['/documenti/documenti/validate-document', 'id' => $model['id']]),
                                    'btnOptions' => ['title' => AmosDocumenti::t('amosdocumenti', 'Publish')]
                                ]);
                            }
                            return $btn;
                        },
                        'reject' => function ($url, $model) {
                            /** @var Documenti $model */
                            $btn = '';
                            if (Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
                                $btn = ModalUtility::addConfirmRejectWithModal([
                                    'modalId' => 'reject-document-modal-id',
                                    'modalDescriptionText' => AmosDocumenti::t('amosdocumenti', '#REJECT_DOCUMENT_MODAL_TEXT'),
                                    'btnText' => AmosIcons::show('minus-circle', ['class' => 'btn btn-tool-secondary']),
                                    'btnLink' => Yii::$app->urlManager->createUrl(['/documenti/documenti/reject-document', 'id' => $model['id']]),
                                    'btnOptions' => ['title' => AmosDocumenti::t('amosdocumenti', 'Reject'), 'class' => 'reject-btns']
                                ]);
                            }
                            return $btn;
                        },
                        'update' => function ($url, $model) {
                            /** @var Documenti $model */
                            $btn = '';
                            if (Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $model])) {
                                $action = '/documenti/documenti/update?id=' . $model->id;
                                $options = \lispa\amos\core\utilities\ModalUtility::getBackToEditPopup($model,
                                    'DocumentValidate', $action, ['class' => 'btn btn-tool-secondary']);
                                return Html::a(\lispa\amos\core\icons\AmosIcons::show('edit'), $action,
                                    $options);
                            }
                            return $btn;
                        }
                    ]
                ],
            ],
            'enableExport' => true
        ],
        'listView' => [
            'itemView' => '_item',
            /*'masonry' => TRUE,
            'masonrySelector' => '.grid',
            'masonryOptions' => [
                'itemSelector' => '.grid-item',
                'columnWidth' => '.grid-sizer',
                'percentPosition' => 'true',
                'gutter' => 20
            ],*/
        ]
    ]);
    ?>
</div>
