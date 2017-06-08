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

use lispa\amos\core\module\Module;
use lispa\amos\core\module\ModuleInterface;
use lispa\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use lispa\amos\documenti\widgets\icons\WidgetIconAllDocumenti;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumenti;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiCategorie;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiCreatedBy;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare;
use Yii;

class AmosDocumenti extends Module implements ModuleInterface
{
    public static $CONFIG_FOLDER = 'config';
    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';
    public $name = 'Notizie';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('amos/' . self::getModuleName() . '/' . $category, $message, $params, $language);
    }

    public static function getModuleName()
    {
        return "documenti";
    }

    public function init()
    {
        parent::init();
		
		\Yii::setAlias('@lispa/amos/' . static::getModuleName() . '/controllers/', __DIR__ . '/controllers/');
        // initialize the module with the configuration loaded from config.php
        Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php'));
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $translationConfiguration = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => Yii::$app->language,
            'basePath' => '@vendor/lispa/amos-' . self::getModuleName() . '/src/messages',
            'fileMap' => [
                'amos/' . self::getModuleName() . '/app' => 'app.php',
            ],
        ];
        if (!YII_ENV_PROD) {
            $translationConfiguration['on missingTranslation'] = ['lispa\amos\core\components\TranslationEventHandler', 'handleMissingTranslation'];
        }
        Yii::$app->getI18n()->translations['amos/' . self::getModuleName() . '/*'] = $translationConfiguration;
    }

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

    public function getWidgetGraphics()
    {
        return [
            WidgetGraphicsUltimiDocumenti::className(),
        ];
    }

}