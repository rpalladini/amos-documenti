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

namespace lispa\amos\documenti\assets;

use yii\web\AssetBundle;

class ModuleDocumentiAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lispa/amos-documenti/src/assets/web';

    public $css = [
        'css/less/documents.less'
        
    ];
    public $js = [
        'js/documents.js'
    ];
    public $depends = [
        'lispa\amos\core\views\assets\AmosCoreAsset',
    ];
}