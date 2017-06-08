<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\controllers
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

namespace lispa\amos\documenti\controllers;

use lispa\amos\dashboard\controllers\base\DashboardController;
use yii\helpers\Url;

class DefaultController extends DashboardController
{
    /**
     * @var string $layout Layout per la dashboard interna.
     */
    public $layout = "@vendor/lispa/amos-core/views/layouts/dashboard_interna";

    /**
     * Lists all DocumentiAllegati models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['/documenti/documenti/all-documents']);
        Url::remember();
        $params = [
            'currentDashboard' => $this->getCurrentDashboard()
        ];
        return $this->render('index', $params);
    }
}
