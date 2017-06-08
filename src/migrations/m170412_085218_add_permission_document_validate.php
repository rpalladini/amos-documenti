<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\migrations
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170412_085218_add_permission_document_validate
 */
class m170412_085218_add_permission_document_validate extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DocumentValidate',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a document with cwh query',
                'ruleName' => \lispa\amos\core\rules\ValidatorUpdateContentRule::className(),
                'parent' => ['VALIDATORE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTI_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['DocumentValidate'],
                    'removeParents' => ['VALIDATORE_DOCUMENTI']
                ]
            ]
        ];
    }
}
