<?php

use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m170529_104009_add_basic_user_perms extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'LETTORE_DOCUMENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ],
            [
                'name' => 'CREATORE_DOCUMENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATED_BASIC_USER']
                ]
            ]
        ];
    }
}
