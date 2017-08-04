<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\news\migrations
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

use lispa\amos\core\migration\libs\common\MigrationCommon;
use lispa\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m170613_094811_update_documents_add_commentable_field
 */
class m170613_094811_update_documents_add_commentable_field extends Migration
{
    private $tablename;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tablename = Documenti::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        try {
            $this->addColumn($this->tablename, 'comments_enabled', $this->boolean()->defaultValue(0)->after('status'));
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Error while add column 'comments_enabled' to " . $this->tablename . " table");
            return false;
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        try {
            $this->dropColumn($this->tablename, 'comments_enabled');
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Error while drop column 'comments_enabled' from " . $this->tablename . " table");
            return false;
        }
        return true;
    }
}
