<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\models
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

namespace lispa\amos\documenti\models;

use lispa\amos\comments\models\CommentInterface;
use lispa\amos\core\views\toolbars\StatsToolbarPanels;
use lispa\amos\cwh\base\datetime;
use lispa\amos\cwh\base\ModelContentInterface;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\attachments\behaviors\FileBehavior;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard;
use lispa\amos\notificationmanager\behaviors\NotifyBehavior;
use lispa\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use pendalf89\filemanager\behaviors\MediafileBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use Yii;

/**
 * This is the model class for table "documenti".
 */
class Documenti extends \lispa\amos\documenti\models\base\Documenti implements ModelContentInterface, CommentInterface
{
    /**
     * @var    string    DOCUMENTI_WORKFLOW    ID del workflow del model
     */
    const DOCUMENTI_WORKFLOW = 'DocumentiWorkflow';

    /**
     * @var    string    DOCUMENTI_WODOCUMENTI_WORKFLOW_STATUS_BOZZARKFLOW        ID dello stato di bozza del workflow del model
     */
    const DOCUMENTI_WORKFLOW_STATUS_BOZZA = 'DocumentiWorkflow/BOZZA';

    /**
     * @var    string    DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE        ID dello stato da validare del workflow del model
     */
    const DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE = 'DocumentiWorkflow/DAVALIDARE';

    /**
     * @var    string    DOCUMENTI_WORKFLOW_STATUS_VALIDATO    ID dello stato validato del workflow del model
     */
    const DOCUMENTI_WORKFLOW_STATUS_VALIDATO = 'DocumentiWorkflow/VALIDATO';

    /**
     * @var    string    DOCUMENTI_WORKFLOW_STATUS_NONVALIDATO    ID dello stato non validato del workflow del model
     */
    const DOCUMENTI_WORKFLOW_STATUS_NONVALIDATO = 'DocumentiWorkflow/NONVALIDATO';

    /**
     * @var    string $regola_pubblicazione Regola di pubblicazione
     */
    public $regola_pubblicazione;

    /**
     * @var    string $destinatari Destinatari
     */
    public $destinatari;

    /**
     * @var    string $validatori Validatori
     */
    public $validatori;

    /**
     * @var    string $distance Distanza
     */
    public $distance;

    /**
     * @var    string $destinatari_pubblicazione Destinatari pubblicazione
     */
    public $destinatari_pubblicazione;

    /**
     * @var    string $destinatari_notifiche Destinatari notifiche
     */
    public $destinatari_notifiche;

    /**
     * @var    mixed $file File
     */
    public $file;

    /**
     * @var $documentMainFile
     */
    public $documentMainFile;

    /**
     * @var $documentAttachments
     */
    public $documentAttachments;

    /**
     * @see    \yii\db\BaseActiveRecord::init()    for more info.
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::DOCUMENTI_WORKFLOW)->getInitialStatusId();
        }
    }

    /**
     * @see    \yii\base\Model::rules()    for more info.
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['destinatari_pubblicazione', 'destinatari_notifiche'], 'safe'],
            [['documentMainFile'], 'required'],
            [['documentAttachments'], 'file','extensions' => 'txt, csv, pdf, txt, doc, docx, xls, xlsx, rtf','maxFiles' => 0],
            [['documentMainFile'], 'file','skipOnEmpty' => true,'extensions' => 'txt, csv, pdf, txt, doc, docx, xls, xlsx, rtf','maxFiles' => 1,'on' => 'update'],
            [['documentMainFile'], 'file','skipOnEmpty' => false,'extensions' => 'txt, csv, pdf, txt, doc, docx, xls, xlsx, rtf','maxFiles' => 1,'on' => 'create'],
        ]);
    }

    /**
     * @see    \yii\base\Component::behaviors()    for more info.
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'mediafile' => [
                'class' => MediafileBehavior::className(),
                'name' => get_class($this),
                'attributes' => [
                    'filemanager_mediafile_id',
                ],
            ],
            'workflow' => [
                'class' => SimpleWorkflowBehavior::className(),
                'defaultWorkflowId' => self::DOCUMENTI_WORKFLOW,
                'propagateErrorsToModel' => true
            ],
            'workflowLog' =>[
                'class' => WorkflowLogFunctionsBehavior::className()
            ],
            'NotifyBehavior' => [
                'class' => NotifyBehavior::className(),
                'conditions' => [],
            ],
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],

        ]);
    }

    /**
     *
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->documentMainFile = $this->getDocumentMainFile()->one();
        $this->documentAttachments = $this->getDocumentAttachments()->one();
    }

    /**
     * @see    \lispa\amos\core\record\Record::representingColumn()    for more info.
     */
    public function representingColumn()
    {
        return [
            'titolo'
        ];
    }

    /**
     *@inheritdoc
     */
    public function getModelLabel()
    {
        return AmosDocumenti::t('amosdocumenti', 'Documenti');
    }

    /**
     *@inheritdoc
     */
    public function getGridViewColumns()
    {
        return  [
            'titolo' => [
                'attribute' => 'titolo',
                'headerOptions' => [
                    'id' => 'titolo'
                ],
                'contentOptions' => [
                    'headers' => 'titolo'
                ]
            ],
            'descrizione' => [
                'attribute' => 'descrizione',
                'format' => 'html',
                'headerOptions' => [
                    'id' => 'descrizione'
                ],
                'contentOptions' => [
                    'headers' => 'descrizione'
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return "documenti/documenti/view";
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus(){
        return self::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus(){
        return self::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus(){
        return self::DOCUMENTI_WORKFLOW_STATUS_BOZZA;
    }

    public function getPluginWidgetClassname()
    {
        return WidgetIconDocumentiDashboard::className();
    }

    /**
     * Getter for $this->documentMainFile;
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentMainFile()
    {
        return $this->hasOneFile('documentMainFile');
    }

    /**
     * Getter for $this->documentAttachments;
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentAttachments()
    {
        return $this->hasMultipleFiles('documentAttachments');
    }

    /**
     * @inheritdoc
     */
    public function isCommentable()
    {
        return $this->comments_enabled;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->titolo;
    }

    /**
     * @return string
     */
    public function getDescription($truncate)
    {
        $ret = $this->descrizione;

        if($truncate){
            $ret = $this->__shortText($this->descrizione,200);
        }
        return $ret;
    }

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosDocumenti::t('amosdocumenti', 'Documento');
    }

    /**
     *
     */
    public function getStatsToolbar(){
        $panels = [];
        $count_comments = 0;

        try{
            $panels = parent::getStatsToolbar();
            $filescount = $this->getFileCount() - 1;
            $panels = ArrayHelper::merge($panels,StatsToolbarPanels::getDocumentsPanel($this,$filescount));
            if($this->isCommentable()) {
                $commentModule = \Yii::$app->getModule('comments');
                if ($commentModule) {
                    $count_comments = $commentModule->countComments($this);
                }
                $panels = ArrayHelper::merge($panels,StatsToolbarPanels::getCommentsPanel($this,$count_comments));
            }
        }catch(Exception $ex){
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $panels;
    }

    /**
     * @return DateTime date begin of publication
     */
    public function getPublicatedFrom()
    {
       return $this->data_pubblicazione;
    }

    /**
     * @return DateTime date end of publication
     */
    public function getPublicatedAt()
    {
        return $this->data_rimozione;
    }

    /**
     * Metodo che mette in relazione la notizia con la singola categoria ad essa associata.
     * Ritorna un ActiveQuery relativo al model DocumentiCategorie.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(\lispa\amos\documenti\models\DocumentiCategorie::className(), ['id' => 'documenti_categorie_id']);
    }
}
