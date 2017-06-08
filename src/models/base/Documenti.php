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

use lispa\amos\documenti\AmosDocumenti;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "documenti".
 *
 * @property    integer $id
 * @property    string $titolo
 * @property    string $sottotitolo
 * @property    string $descrizione_breve
 * @property    string $descrizione
 * @property    string $metakey
 * @property    string $metadesc
 * @property    integer $primo_piano
 * @property    integer $filemanager_mediafile_id
 * @property    integer $hits
 * @property    integer $abilita_pubblicazione
 * @property    integer $in_evidenza
 * @property    string $data_pubblicazione
 * @property    string $data_rimozione
 * @property    integer $documenti_categorie_id
 * @property    string $status
 * @property    string $created_at
 * @property    string $updated_at
 * @property    string $deleted_at
 * @property    integer $created_by
 * @property    integer $updated_by
 * @property    integer $deleted_by
 *
 * @property \lispa\amos\documenti\models\DocumentiCategorie $documentiCategorie
 * @property \lispa\amos\documenti\models\DocumentiAllegati $documentiAllegati
 * @property \lispa\amos\upload\models\FilemanagerMediafile $documentoPrincipale
 */
class Documenti extends \lispa\amos\core\record\Record
{

    /**
     * @see    \yii\db\ActiveRecord::tableName()    for more info.
     */
    public static function tableName()
    {
        return 'documenti';
    }

    /**
     * @see    \yii\base\Model::rules()    for more info.
     */
    public function rules()
    {
        return [
            [['descrizione', 'metakey', 'metadesc'], 'string'],
            [['primo_piano', 'filemanager_mediafile_id', 'hits', 'abilita_pubblicazione', 'in_evidenza', 'documenti_categorie_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['data_pubblicazione', 'data_rimozione', 'created_at', 'updated_at', 'deleted_at', 'status'], 'safe'],
            [['documenti_categorie_id', 'titolo', 'status', 'data_pubblicazione', 'data_rimozione'], 'required'],
            ['data_pubblicazione', 'compare', 'compareAttribute' => 'data_rimozione', 'operator' => '<='],
            ['data_rimozione', 'compare', 'compareAttribute' => 'data_pubblicazione', 'operator' => '>='],
            //['data_pubblicazione', 'checkDate'],
            [['titolo', 'sottotitolo', 'descrizione_breve'], 'string', 'max' => 255],
        ];
    }

    /**
     * @see    \lispa\amos\core\record\Record::attributeLabels()    for more info.
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'id' => AmosDocumenti::t('amosdocumenti', 'Id'),
                'titolo' => AmosDocumenti::t('amosdocumenti', 'Titolo'),
                'sottotitolo' => AmosDocumenti::t('amosdocumenti', 'Sottotitolo'),
                'descrizione_breve' => AmosDocumenti::t('amosdocumenti', 'Abstract (testo breve che comparirà in home)'),
                'descrizione' => AmosDocumenti::t('amosdocumenti', 'Testo'),
                'metakey' => AmosDocumenti::t('amosdocumenti', 'Meta key'),
                'metadesc' => AmosDocumenti::t('amosdocumenti', 'Meta descrizione'),
                'primo_piano' => AmosDocumenti::t('amosdocumenti', 'Pubblica sul sito'),
                'filemanager_mediafile_id' => AmosDocumenti::t('amosdocumenti', 'Documento pricipale'),
                'in_evidenza' => AmosDocumenti::t('amosdocumenti', 'In evidenza'),
                'hits' => AmosDocumenti::t('amosdocumenti', 'Visualizzazioni'),
                'abilita_pubblicazione' => AmosDocumenti::t('amosdocumenti', 'Abilita pubblicazione'),
                'data_pubblicazione' => AmosDocumenti::t('amosdocumenti', 'Data pubblicazione'),
                'data_rimozione' => AmosDocumenti::t('amosdocumenti', 'Data fine pubblicazione'),
                'documenti_categorie_id' => AmosDocumenti::t('amosdocumenti', 'Categoria'),
                'status' => AmosDocumenti::t('amosdocumenti', 'Stato'),
                'created_at' => AmosDocumenti::t('amosdocumenti', 'Creato il'),
                'updated_at' => AmosDocumenti::t('amosdocumenti', 'Aggiornato il'),
                'deleted_at' => AmosDocumenti::t('amosdocumenti', 'Cancellato il'),
                'created_by' => AmosDocumenti::t('amosdocumenti', 'Creato da'),
                'updated_by' => AmosDocumenti::t('amosdocumenti', 'Aggiornato da'),
                'deleted_by' => AmosDocumenti::t('amosdocumenti', 'Cancellato da'),
            ]);
    }

    /**
     * Metodo che mette in relazione la notizia con la singola categoria ad essa associata.
     * Ritorna un ActiveQuery relativo al model DocumentiCategorie.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentiCategorie()
    {
        return $this->hasOne(\lispa\amos\documenti\models\DocumentiCategorie::className(), ['id' => 'documenti_categorie_id']);
    }

    /**
     * Metodo che mette in relazione la notizia con i diversi allegati ad essa associata.
     * Ritorna un ActiveQuery relativo al model DocumentiAllegati.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentiAllegati()
    {
        return $this->hasMany(\lispa\amos\documenti\models\DocumentiAllegati::className(), ['documenti_id' => 'id']);
    }

    /**
     * Metodo che mette in relazione la notizia con i diversi media file ad essa associata.
     * Ritorna un ActiveQuery relativo al model FilemanagerMediafile.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilemanagerMediafiles()
    {
        return $this->hasMany(\lispa\amos\upload\models\FilemanagerMediafile::className(), ['id' => 'filemanager_mediafile_id'])->viaTable('documenti_allegati', ['documenti_id' => 'id']);
    }

    /**
     * Metodo che mette in relazione la notizia con la singola immagine ad essa associata.
     * Ritorna un ActiveQuery relativo al model FilemanagerMediafile.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentoPrincipale()
    {
        return $this->hasOne(\lispa\amos\upload\models\FilemanagerMediafile::className(), ['id' => 'filemanager_mediafile_id']);
    }

}