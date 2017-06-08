<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\widgetRs\graphics
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

namespace lispa\amos\documenti\widgets\graphics;

use lispa\amos\core\widget\WidgetGraphic;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\search\DocumentiSearch;

class WidgetGraphicsUltimiDocumenti extends WidgetGraphic
{
    /**
     * @inheritdocF
     */
    public function init()
    {
        parent::init();

        $this->setCode('ULTIME_DOCUMENTI_GRAPHIC');
        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', 'Ultimi documenti'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', 'Elenca gli ultimi documenti'));
    }

    public function getHtml()
    {
        $listaDocumenti = (new DocumentiSearch())->lastDocuments($_GET, 3);

        return $this->render('ultimi_documenti', [
            'listaDocumenti' => $listaDocumenti,
            'widget' => $this,
            'toRefreshSectionId' => 'widgetGraphicLatestDocumenti'
        ]);
    }
}