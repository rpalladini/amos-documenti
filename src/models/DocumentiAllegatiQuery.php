<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\models
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

namespace lispa\amos\documenti\models;

use lispa\amos\cwh\query\CwhActiveQuery;

//use creocoder\taggable\TaggableQueryBehavior;
//use yii\helpers\ArrayHelper;


/**
 * Class Documenti AllegatiQuery
 * @package lispa\amos\documenti\models * File generato automaticamente, verificarne
 * il contenuto prima di utilizzarlo in produzione
 */
class DocumentiAllegatiQuery extends CwhActiveQuery
{
    // TODO verificare se serve ancora questa classe, altrimenti eliminare completamente il file.

    /**
     * @return array
     * da scommentare se si utilizzano i tag
     */
    //public function behaviors()
    //{
    //    return ArrayHelper::merge(
    //        parent::behaviors(), [
    //            TaggableQueryBehavior::className()
    //        ]
    //    );   
    //}

//    /**
//     * TODO verificare se serve questo metodo
//     *
//     * @return \yii\db\ActiveQuery
//     */
//    public function attive()
//    {
//        //Questo è solo un esempio, verificare che i campi e le tabelle siano corretti
//        return $this->innerJoin('documentiAllegati_stato', 'documentiAllegati.documentiAllegati_stato_id = documentiAllegati_stato.id AND documentiAllegati_stato.nome = :stato_nome', [
//            ':stato_nome' => 'Attiva'
//        ]);
//    }
}
