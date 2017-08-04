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

namespace lispa\amos\documenti\widgets\icons;


use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\documenti\AmosDocumenti;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Application as Web;
use lispa\amos\dashboard\models\AmosUserDashboards;
use lispa\amos\dashboard\models\AmosUserDashboardsWidgetMm;
use lispa\amos\dashboard\models\AmosWidgets;


class WidgetIconDocumentiDashboard extends WidgetIcon
{
    public function init()
    {
        parent::init();

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', 'Documenti'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', 'Modulo documenti'));

        $this->setIcon('file-text-o');
        //$this->setIconFramework();
        
        $this->setUrl(['/documenti']);

        $this->setCode('DOCUMENTI_MODULE');
        $this->setModuleName('documenti-dashboard');
        $this->setNamespace(__CLASS__);
        if (Yii::$app instanceof Web) {
            $this->setBulletCount($this->getBulletCountChildWidgets());
        }
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));

    }

    public function getOptions()
    {
        $options = parent::getOptions();

        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => $this->getWidgetsIcon()]);
    }

    /* TEMPORANEA */
    public function getWidgetsIcon()
    {
        $widgets = [];

        $WidgetIconDocumentiCategorie = new WidgetIconDocumentiCategorie();
        if ($WidgetIconDocumentiCategorie->isVisible()) {
            $widgets[] = $WidgetIconDocumentiCategorie->getOptions();
        }

        $WidgetIconDocumentiCreatedBy = new WidgetIconDocumentiCreatedBy();
        if ($WidgetIconDocumentiCreatedBy->isVisible()) {
            $widgets[] = $WidgetIconDocumentiCreatedBy->getOptions();
        }

        return $widgets;
    }

    /**
     *
     * @return int - the sum of bulletCount internal widget
     *
     */
    private function getBulletCountChildWidgets()
    {
        /** @var AmosUserDashboards $userModuleDashboard */
        $userModuleDashboard = AmosUserDashboards::findOne([
            'user_id' => \Yii::$app->user->id,
            'module' => AmosDocumenti::getModuleName()
        ]);
        if (is_null($userModuleDashboard)) return 0;

        $listWidgetChild = $userModuleDashboard->amosUserDashboardsWidgetMms;
        if (is_null($listWidgetChild)) return 0;
        $count = 0;
        /** @var AmosUserDashboardsWidgetMm $widgetChild */
        foreach ($listWidgetChild as $widgetChild) {
            if ($widgetChild->amos_widgets_classname != $this->getNamespace()) {
                $amosWidget = AmosWidgets::findOne(['classname' => $widgetChild->amos_widgets_classname]);
                if ($amosWidget->type == AmosWidgets::TYPE_ICON) {
                    $widget = \Yii::createObject($widgetChild->amos_widgets_classname);
                    $val = $widget->getBulletCount();

                    $bulletCount = empty($val) ? 0 : $val;
                    $count = $count + $bulletCount;
                }
            }
        }
        return $count;
    }
}