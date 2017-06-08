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

use lispa\amos\core\views\DataProviderView;
use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var lispa\amos\documenti\models\search\DocumentiSearch $searchModel
 */
$this->title = AmosDocumenti::t('amosdocumenti', 'Documenti');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documenti-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <?php
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'titolo',
                'sottotitolo',
                'descrizione_breve',
                'documenti_categorie_id' => [
                    'attribute' => 'documentiCategorie.titolo',
                    'label' => AmosDocumenti::t('amosdocumenti', 'Categoria'),
                ],
                [
                    'attribute' => 'data_pubblicazione',
                    'format' => ['date', (isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
                [
                    'class' => 'lispa\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
        'listView' => [
            'itemView' => '_itemUtenti'
        ],
    ]);    
    ?>

</div>
