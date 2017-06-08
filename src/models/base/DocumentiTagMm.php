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
 * This is the base-model class for table "documenti_tag_mm".
 *
 * @property    integer $documenti_id
 * @property    integer $tag_id
 */
class DocumentiTagMm extends Record
{
    /**
     * @see    \yii\db\ActiveRecord::tableName()    for more info.
     */
    public static function tableName()
    {
        return 'documenti_tag_mm';
    }

    /**
     * @see    \yii\base\Model::rules()    for more info.
     */
    public function rules()
    {
        return [
            [['documenti_id', 'tag_id'], 'required'],
            [['documenti_id', 'tag_id'], 'integer']
        ];
    }

    /**
     * @see    \lispa\amos\core\record\Record::attributeLabels()    for more info.
     */
    public function attributeLabels()
    {
        return [
            'documenti_id' => AmosDocumenti::t('amosdocumenti', 'Documenti ID'),
            'tag_id' => AmosDocumenti::t('amosdocumenti', 'Tag ID'),
        ];
    }
}
