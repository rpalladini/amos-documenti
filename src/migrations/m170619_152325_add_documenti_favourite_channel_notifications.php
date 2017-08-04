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
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m170619_152325_add_documenti_favourite_channel_notifications
 */
class m170619_152325_add_documenti_favourite_channel_notifications extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $notifyModule = Yii::$app->getModule('notify');
        if (is_null($notifyModule)) {
            MigrationCommon::printConsoleMessage(AmosDocumenti::t('amosdocumenti', 'Notify module not installed. Nothing to do.'));
            return true;
        }
        $retval = \lispa\amos\notificationmanager\AmosNotify::manageNewChannelNotifications(
            Documenti::className(),
            \lispa\amos\notificationmanager\models\NotificationChannels::CHANNEL_FAVOURITES,
            \lispa\amos\notificationmanager\models\NotificationChannels::MANAGE_UP);
        if (!$retval['success']) {
            foreach ($retval['errors'] as $error) {
                MigrationCommon::printConsoleMessage($error);
            }
        }
        return $retval['success'];
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $notifyModule = Yii::$app->getModule('notify');
        if (is_null($notifyModule)) {
            MigrationCommon::printConsoleMessage(AmosDocumenti::t('amosdocumenti', 'Notify module not installed. Nothing to do.'));
            return true;
        }
        $retval = \lispa\amos\notificationmanager\AmosNotify::manageNewChannelNotifications(
            Documenti::className(),
            \lispa\amos\notificationmanager\models\NotificationChannels::CHANNEL_FAVOURITES,
            \lispa\amos\notificationmanager\models\NotificationChannels::MANAGE_DOWN);
        if (!$retval['success']) {
            foreach ($retval['errors'] as $error) {
                MigrationCommon::printConsoleMessage($error);
            }
        }
        return $retval['success'];
    }
}
