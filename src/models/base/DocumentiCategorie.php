<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\models\base
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

namespace lispa\amos\documenti\models\base;

use lispa\amos\core\record\Record;
use lispa\amos\documenti\AmosDocumenti;

/**
 * This is the base-model class for table "documenti_categorie".
 *
 * @property    integer $id
 * @property    string $titolo
 * @property    string $sottotitolo
 * @property    string $descrizione_breve
 * @property    string $descrizione
 * @property    integer $filemanager_mediafile_id
 * @property    string $created_at
 * @property    string $updated_at
 * @property    string $deleted_at
 * @property    integer $created_by
 * @property    integer $updated_by
 * @property    integer $deleted_by
 *
 * @property \lispa\amos\documenti\models\Documenti $documenti
 */
class DocumentiCategorie extends Record
{
    /**
     * @see    \yii\db\ActiveRecord::tableName()    for more info.
     */
    public static function tableName()
    {
        return 'documenti_categorie';
    }

    /**
     * @see    \yii\base\Model::rules()    for more info.
     */
    public function rules()
    {
        return [
            [['descrizione'], 'string'],
            [['filemanager_mediafile_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['titolo', 'sottotitolo', 'descrizione_breve'], 'string', 'max' => 255]
        ];
    }

    /**
     * @see    Record::attributeLabels()    for more info.
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosDocumenti::t('amosdocumenti', 'Id'),
            'titolo' => AmosDocumenti::t('amosdocumenti', 'Titolo'),
            'sottotitolo' => AmosDocumenti::t('amosdocumenti', 'Sottotitolo'),
            'descrizione_breve' => AmosDocumenti::t('amosdocumenti', 'Descrizione breve'),
            'descrizione' => AmosDocumenti::t('amosdocumenti', 'Descrizione'),
            'filemanager_mediafile_id' => AmosDocumenti::t('amosdocumenti', 'Immagine'),
            'created_at' => AmosDocumenti::t('amosdocumenti', 'Creato il'),
            'updated_at' => AmosDocumenti::t('amosdocumenti', 'Aggiornato il'),
            'deleted_at' => AmosDocumenti::t('amosdocumenti', 'Cancellato il'),
            'created_by' => AmosDocumenti::t('amosdocumenti', 'Creato da'),
            'updated_by' => AmosDocumenti::t('amosdocumenti', 'Aggiornato da'),
            'deleted_by' => AmosDocumenti::t('amosdocumenti', 'Cancellato da')
        ];
    }

    /**
     * Metodo che mette in relazione la categoria con le notizie ad essa associata.
     * Ritorna un ActiveQuery relativo al model Documenti.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumenti()
    {
        return $this->hasMany(\lispa\amos\documenti\models\Documenti::className(), ['documenti_categorie_id' => 'id']);
    }
}
