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

?>

/*
* Personalizzare a piacimento la vista
* $model è il model legato alla tabella del db
* $buttons sono i tasti del template standard {view}{update}{delete}
*/

<div class="listview-container">
    <div class="row">
        <div id="bk-listViewElementDocumenti-allegati" class="col-xs-12 bk-listViewElementDocumenti-allegati">
            <div class="col-xs-8 col-md-8">
                <h2><?= $model ?></h2>
                <div class="bk-infoElementList">
                    <p>################# PERSONALIZZARE A PIACIMENTO L&#39;HTML ################</p>
                </div>
                <div class="col-xs-4 col-md-4">
                    <?= $buttons ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="col-xs-4 col-md-4">
                <p>### PERSONALIZZA ###</p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>