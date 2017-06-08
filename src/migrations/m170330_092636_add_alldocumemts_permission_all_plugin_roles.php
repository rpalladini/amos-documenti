<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m170330_092636_add_alldocumemts_permission_all_plugin_roles extends AmosMigrationPermissions
{
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'AMMINISTRATORE_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore documenti',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => \lispa\amos\documenti\widgets\icons\WidgetIconAllDocumenti::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI', 'CREATORE_DOCUMENTI', 'LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumenti::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI', 'CREATORE_DOCUMENTI', 'LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
        ];
    }
}
