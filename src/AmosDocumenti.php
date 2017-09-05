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

namespace lispa\amos\documenti;

use lispa\amos\core\module\AmosModule;
use lispa\amos\core\module\ModuleInterface;
use lispa\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use lispa\amos\documenti\widgets\icons\WidgetIconAllDocumenti;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumenti;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiCategorie;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiCreatedBy;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare;
use Yii;

/**
 * Class AmosDocumenti
 * @package lispa\amos\documenti
 */
class AmosDocumenti extends AmosModule implements ModuleInterface
{
    public static $CONFIG_FOLDER = 'config';
    
    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';
    
    public $name = 'Documenti';
    
    public $controllerNamespace = 'lispa\amos\documenti\controllers';
    
    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return "documenti";
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        \Yii::setAlias('@lispa/amos/' . static::getModuleName() . '/controllers/', __DIR__ . '/controllers/');
        // initialize the module with the configuration loaded from config.php
        Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php'));
    }
    
    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconDocumenti::className(),
            WidgetIconDocumentiCategorie::className(),
            WidgetIconDocumentiCreatedBy::className(),
            WidgetIconDocumentiDaValidare::className(),
            WidgetIconDocumentiDashboard::className(),
            WidgetIconAllDocumenti::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return [
            WidgetGraphicsUltimiDocumenti::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [
            'Documenti' => __NAMESPACE__ . '\\' . 'models\Documenti',
            'DocumentiCategorie' => __NAMESPACE__ . '\\' . 'models\DocumentiCategorie',
            'DocumentiTagMm' => __NAMESPACE__ . '\\' . 'models\DocumentiTagMm',
        ];
    }
    
    /**
     * This method return the session key that must be used to add in session
     * the url from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKey()
    {
        return 'beginCreateNewUrl_' . self::getModuleName();
    }
}
