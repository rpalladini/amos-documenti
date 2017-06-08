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

/**
 * This is the base-model class for table "documenti_allegati".
 *
 * @property    string $titolo
 * @property    string $descrizione
 * @property    integer $filemanager_mediafile_id
 * @property    integer $documenti_id
 * @property    string $created_at
 * @property    string $updated_at
 * @property    string $deleted_at
 * @property    integer $created_by
 * @property    integer $updated_by
 * @property    integer $deleted_by
 *
 * @property \lispa\amos\documenti\models\FilemanagerMediafile $filemanagerMediafile
 * @property \lispa\amos\documenti\models\Documenti $documenti
 */
class DocumentiAllegati extends \lispa\amos\core\record\Record
{
    /**
     * @see    \yii\db\ActiveRecord::tableName()    for more info.
     */
    public static function tableName()
    {
        return 'documenti_allegati';
    }

    /**
     * @see    \yii\base\Model::rules()    for more info.
     */
    public function rules()
    {
        return [
            [['titolo', 'filemanager_mediafile_id', 'documenti_id'], 'required'],
            [['descrizione'], 'string'],
            [['filemanager_mediafile_id', 'documenti_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['titolo'], 'string', 'max' => 255]
        ];
    }

    /**
     * @see    \lispa\amos\core\record\Record::attributeLabels()    for more info.
     */
    public function attributeLabels()
    {
        return [
            'titolo' => AmosDocumenti::t('amosdocumenti', 'Titolo'),
            'descrizione' => AmosDocumenti::t('amosdocumenti', 'Descrizione'),
            'filemanager_mediafile_id' => AmosDocumenti::t('amosdocumenti', 'Filemanager Mediafile ID'),
            'documenti_id' => AmosDocumenti::t('amosdocumenti', 'Documenti ID'),
            'created_at' => AmosDocumenti::t('amosdocumenti', 'Creato il'),
            'updated_at' => AmosDocumenti::t('amosdocumenti', 'Aggiornato il'),
            'deleted_at' => AmosDocumenti::t('amosdocumenti', 'Cancellato il'),
            'created_by' => AmosDocumenti::t('amosdocumenti', 'Creato da'),
            'updated_by' => AmosDocumenti::t('amosdocumenti', 'Aggiornato da'),
            'deleted_by' => AmosDocumenti::t('amosdocumenti', 'Cancellato da'),
        ];
    }

    /**
     * Metodo che mette in relazione l'allegato con il relativo media file ad esso associata.
     * Ritorna un ActiveQuery relativo al model FilemanagerMediafile.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilemanagerMediafile()
    {
        return $this->hasOne(\lispa\amos\upload\models\FilemanagerMediafile::className(), ['id' => 'filemanager_mediafile_id']);
    }

    /**
     * Metodo che mette in relazione l'allegato con la singola notizia ad esso associata.
     * Ritorna un ActiveQuery relativo al model Documenti.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumenti()
    {
        return $this->hasOne(\lispa\amos\documenti\models\Documenti::className(), ['id' => 'documenti_id']);
    }
}
