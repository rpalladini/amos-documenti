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

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "documenti_allegati".
 */
class DocumentiAllegati extends \lispa\amos\documenti\models\base\DocumentiAllegati
{
    /**
     * @var    string $regola_pubblicazione Regola di pubblicazione
     */
    //public $regola_pubblicazione;

    /**
     * @var    string $destinatari Destinatari
     */
    //public $destinatari;

    /**
     * @var    string $validatori Validatori
     */
    //public $validatori;

    /**
     * @var    mixed $file File
     */
    public $file;

    /**
     * @see    \lispa\amos\core\record\Record::representingColumn()    for more info.
     */
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
            'titolo'
        ];
    }

    /**
     * Scommentare le seguenti function(), gli attributi sopra  e gli "use"
     * nel caso il modulo necessiti di regole di pubblicazione e personalizzarle
     * a piacimento. E' necessario per poter utilizzare le regole di
     * pubblicazione creare la classe Documenti AllegatiQuery.php
     * che conterrà le query specifiche per gestire la pubblicazione
     * dei contenuti per differenti destinatari.
     * Nelle function() sotto facciamo il merge con le altre function()
     * eventualmente già presenti per il model
     */


    /**
     * @see    \yii\base\Model::rules()    for more info.
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            //[['regola_pubblicazione', 'destinatari', 'validatori'], 'safe'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, txt, pdf, txt, doc, docx, xls, xlsx, rtf, gif, bmp', 'maxFiles' => 1],
        ]);
    }

    /**
     * @see    \lispa\amos\core\record\Record::attributeLabels()    for more info.
     */
//    public function attributeLabels()
//    {
//        return
//            ArrayHelper::merge(
//                parent::attributeLabels(),
//                [
//                    'tagValues' => '',
//                    'regola_pubblicazione' => 'Pubblicata per',
//                    'destinatari' => 'Per i condominii',
//                ]);
//    }

    /**
     * @see    \yii\base\Component::behaviors()    for more info.
     */
//    public function behaviors()
//    {
//        return ArrayHelper::merge(parent::behaviors(), [
//            'CwhNetworkBehaviors' => [
//                'class' => CwhNetworkBehaviors::className(),
//            ]
//        ]);
//    }

    /**
     * @see    \yii\db\ActiveRecord::find()    for more info.
     */
//    public static function find()
//    {
//        $Documenti AllegatiQuery = new Documenti AllegatiQuery(get_called_class());
//        $Documenti AllegatiQuery->andWhere('documenti Allegati.deleted_at IS NULL');
//        return $Documenti AllegatiQuery;
//    }
}
