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

use lispa\amos\core\views\DataProviderView;
use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var lispa\amos\documenti\models\search\DocumentiAllegatiSearch $searchModel
 */

$this->title = AmosDocumenti::t('amosdocumenti', 'Allegati documento');
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', 'Documenti'), 'url' => '/documenti'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documenti-allegati-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'titolo',
                'descrizione:ntext',
                'filemanager_mediafile_id',
                'documenti_id',
                [
                    'attribute' => 'created_at',
                    'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']
                ],
                [
                    'class' => 'lispa\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
    ]); ?>

</div>
