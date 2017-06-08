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

use lispa\amos\core\views\AmosGridView;
use yii\helpers\Html;
use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var lispa\amos\documenti\models\search\DocumentiCategorieSearch $searchModel
 */

$this->title = AmosDocumenti::t('amosdocumenti', 'Categorie documenti');
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', 'Documenti'), 'url' => '/documenti'];
?>
<div class="documenti-categorie-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'columns' => [
            'filemanager_mediafile_id' => [
                'label' => 'Icona',
                'format' => 'html',
                'value' => function ($model) {
                    $url = '/img/img_default.jpg';
                    if (!is_null($model->documentCategoryImage)) {
                        $url = $model->documentCategoryImage->getUrl('square_small');
                    }
                    return Html::img($url, ['class' => 'gridview-image', 'alt' => AmosDocumenti::t('amosdocumenti', 'Immagine della categoria')]);
                }
            ],
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione:ntext',
            [
                'class' => 'lispa\amos\core\views\grid\ActionColumn',
            ],
        ],
    ]); ?>

</div>
