<?php

use yii\db\Migration;
use lispa\amos\attachments\components\FileImport;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\models\DocumentiAllegati;
use yii\db\Query;

class m170518_135046_migrate_documents_and_attachments extends Migration
{
    /**
     * @var string $backendDir The entire path of the 'backend' directory
     */
    private $backendDir;

    public function safeUp()
    {
        Yii::getLogger()->flushInterval = 1;
        Yii::$app->log->targets = [];
        $ok = $this->migrateDocuments();
        return $ok;
    }

    private function migrateDocuments()
    {
        $found = $this->searchBackendDir();
        if ($found) {
            $this->backendDir .= 'backend' . DIRECTORY_SEPARATOR . 'web';
            $query = new Query();
            $query->from(\lispa\amos\documenti\models\Documenti::tableName())->orderBy(['id' => SORT_ASC]);
            $allDocuments = $query->all();
            foreach ($allDocuments as $document) {
                $documents = new \lispa\amos\documenti\models\Documenti($document);
                $documents->detachBehaviors();
                $this->printConsoleMsg('********************************************************************************************************************************************************************');
                $this->migrateSingleDocument($documents);
                $this->migrateDocumentsAttachments($documents);
            }
        } else {
            $this->printConsoleMsg('Cartella backend non trovata');
        }
        return $found;

    }

    /**
     * This method search the 'backend' directory
     * @return bool
     */
    private function searchBackendDir()
    {
        $this->backendDir = __DIR__;
        $lastDirChar = substr($this->backendDir, -1);
        if ($lastDirChar != DIRECTORY_SEPARATOR) {
            $this->backendDir .= DIRECTORY_SEPARATOR;
        }
        $found = false;
        while (!$found) {
            $this->backendDir .= '..' . DIRECTORY_SEPARATOR;
            $dirHandle = opendir($this->backendDir);
            if ($dirHandle) {
                $dirElement = readdir($dirHandle);
                while (!$found && $dirElement) {
                    if (strpos($dirElement, 'backend') !== false) {
                        $found = true;
                    } else {
                        $dirElement = readdir($dirHandle);
                    }
                }
                closedir($dirHandle);
            }
        }
        return $found;
    }

    /**
     * This method print a console message
     * @param $msg
     */
    private function printConsoleMsg($msg)
    {
        print_r($msg);
        print_r("\n");
    }

    /**
     * This method migrate discussions image
     * @param DiscussioniTopic $discussions
     * @return array|bool
     */
    private function migrateSingleDocument($documents)
    {
        if (!$documents->filemanager_mediafile_id) {
            $this->printConsoleMsg('Document ID = ' . $documents->id . " => Il documento non ha allegato principale.");
            return false;
        }
        $documentsImageUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $documents->filemanager_mediafile_id])->scalar();
        if (!$documentsImageUrl) {
            $this->printConsoleMsg('Document ID = ' . $documents->id . " => Url documento principale non trovato");
            return false;
        }
        $filePath = $this->backendDir . $documentsImageUrl;
        if (!file_exists($filePath)) {
            $this->printConsoleMsg('Document ID = ' . $documents->id . " => Allegato  '" . $filePath . "' non presente sul file system.");
            return false;
        }
        $ok = $this->migrateFile($documents, 'documentMainFile', $filePath);
        if (!$ok) {
            $this->printConsoleMsg('Document ID = ' . $documents->id . " => Errore durante la migrazione del documento principale '" . $filePath . "'");
        } else {
            $this->printConsoleMsg('Document  ID = ' . $documents->id . ' => Migrazione documento principale ok');
        }
        return $ok;
    }

    /**
     * This method migrate one file from old folder to new folder and then update database
     * @param DiscussioniTopic $documents
     * @param string $attribute
     * @param string $filePath
     * @return array
     */
    private function migrateFile($documents, $attribute, $filePath)
    {
        $fileImport = new FileImport();
        $ok = $fileImport->importFileForModel($documents, $attribute, $filePath);
        return $ok;
    }

    /**
     * This method migrate all discussions attachments
     * @param documentMainFaile $documents
     */
    private function migrateDocumentsAttachments($documents)
    {
        $documentsAttachmentsIds = (new Query())->select(['filemanager_mediafile_id'])->from(\lispa\amos\documenti\models\base\DocumentiAllegati::tableName())->where(['documenti_id' => $documents->id])->column();
        if (!count($documentsAttachmentsIds)) {
            $this->printConsoleMsg('Documenti ID = ' . $documents->id . ' => Allegati non presenti.');
        }
        foreach ($documentsAttachmentsIds as $documentsAttachmentId) {
            $documentsAttachmentUrl = (new Query())->select(['url'])->from('filemanager_mediafile')->where(['id' => $documentsAttachmentId])->scalar();
            if (!$documentsAttachmentUrl) {
                $this->printConsoleMsg('Documenti ID = ' . $documents->id . "; Filemanager media file id = " . $documentsAttachmentId . " => Url allegato non trovato");
                continue;
            }
            $filePath = $this->backendDir . $documentsAttachmentUrl;
            $ok = $this->migrateFile($documents, 'documentAttachments', $filePath);
            if (!$ok) {
                $this->printConsoleMsg('Documenti ID = ' . $documents->id . "; Filemanager media file id = " . $documentsAttachmentId . " => Errore durante la migrazione dell'allegato '" . $filePath . "'");
            } else {
                $this->printConsoleMsg('Documenti ID = ' . $documents->id . "; Filemanager media file id = " . $documentsAttachmentId . ' => Migrazione allegato del documento ok');
            }
        }
    }

    public function down()
    {
        echo "m170518_135046_migrate_documents_and_attachments cannot be reverted.\n";

        return true;
    }
}
