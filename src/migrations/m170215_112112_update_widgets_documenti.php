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

use lispa\amos\core\migration\AmosMigrationWidgets;
use lispa\amos\dashboard\models\AmosWidgets;

/**
 * Class m170215_112112_update_widgets_documenti
 */
class m170215_112112_update_widgets_documenti extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = array_merge(
            $this->initIconWidgetsConf(),
            $this->initGraphicWidgetsConf()
        );
    }

    /**
     * Init the icon widgets configurations
     * @return array
     */
    private function initIconWidgetsConf()
    {
        return [
            [
                'classname' => lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'update' => true
            ],
            [
                'classname' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumenti::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 10,
                'update' => true
            ],
            [
                'classname' => lispa\amos\documenti\widgets\icons\WidgetIconDocumentiCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 30,
                'update' => true
            ],
            [
                'classname' => \lispa\amos\documenti\widgets\icons\WidgetIconAllDocumenti::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 40,
                'update' => true
            ],
            [
                'classname' => lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 50,
                'update' => true
            ],
            [
                'classname' => lispa\amos\documenti\widgets\icons\WidgetIconDocumentiCategorie::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 60,
                'update' => true
            ]
        ];
    }

    /**
     * Init the graphic widgets configurations
     * @return array
     */
    private function initGraphicWidgetsConf()
    {
        return [
            [
                'classname' => \lispa\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 1,
                'update' => true
            ]
        ];
    }
}
