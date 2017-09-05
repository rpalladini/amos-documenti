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

use lispa\amos\documenti\i18n\grammar\DocumentsGrammar;
use lispa\amos\attachments\behaviors\FileBehavior;
use lispa\amos\comments\models\CommentInterface;
use lispa\amos\core\views\toolbars\StatsToolbarPanels;
use lispa\amos\cwh\base\ModelContentInterface;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard;
use lispa\amos\notificationmanager\behaviors\NotifyBehavior;
use lispa\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use pendalf89\filemanager\behaviors\MediafileBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use lispa\amos\core\interfaces\ViewModelInterface;
use yii\helpers\Url;

/**
 * Class Documenti
 *
 * This is the model class for table "documenti".
 *
 * @method \cornernote\workflow\manager\components\WorkflowDbSource getWorkflowSource()
 * @method \yii\db\ActiveQuery hasOneFile($attribute = 'file', $sort = 'id')
 * @method \yii\db\ActiveQuery hasMultipleFiles($attribute = 'file', $sort = 'id')
 * @method string|null getRegolaPubblicazione()
 * @method array getTargets()
 *
 * @package lispa\amos\documenti\models
 */
class Documenti extends \lispa\amos\documenti\models\base\Documenti implements ModelContentInterface, CommentInterface, ViewModelInterface
{
    // Workflow ID
    const DOCUMENTI_WORKFLOW = 'DocumentiWorkflow';
    
    // Workflow states IDS
    const DOCUMENTI_WORKFLOW_STATUS_BOZZA = 'DocumentiWorkflow/BOZZA';
    const DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE = 'DocumentiWorkflow/DAVALIDARE';
    const DOCUMENTI_WORKFLOW_STATUS_VALIDATO = 'DocumentiWorkflow/VALIDATO';
    const DOCUMENTI_WORKFLOW_STATUS_NONVALIDATO = 'DocumentiWorkflow/NONVALIDATO';
    
    /**
     * All the scenarios listed below are for the wizard.
     */
    const SCENARIO_INTRODUCTION = 'scenario_introduction';
    const SCENARIO_DETAILS = 'scenario_details';
    const SCENARIO_PUBLICATION = 'scenario_publication';
    const SCENARIO_SUMMARY = 'scenario_summary';
    
    /**
     * @var string $regola_pubblicazione Regola di pubblicazione
     */
    public $regola_pubblicazione;
    
    /**
     * @var string $destinatari Destinatari
     */
    public $destinatari;
    
    /**
     * @var string $validatori Validatori
     */
    public $validatori;
    
    /**
     * @var string $distance Distanza
     */
    public $distance;
    
    /**
     * @var string $destinatari_pubblicazione Destinatari pubblicazione
     */
    public $destinatari_pubblicazione;
    
    /**
     * @var string $destinatari_notifiche Destinatari notifiche
     */
    public $destinatari_notifiche;
    
    /**
     * @var mixed $file File
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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::DOCUMENTI_WORKFLOW)->getInitialStatusId();
            $this->data_pubblicazione = date("Y-m-d");
            $query = new Query();
            $categories = $query->from(DocumentiCategorie::tableName())->all();
            $countCategories = count($categories);
            if ($countCategories == 1) {
                $this->documenti_categorie_id = $categories[0]['id'];
            }
        }
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['destinatari_pubblicazione', 'destinatari_notifiche'], 'safe'],
            [['documentMainFile'], 'required'],
            [['documentAttachments'], 'file', 'extensions' => 'txt, csv, pdf, txt, doc, docx, xls, xlsx, rtf', 'maxFiles' => 0],
            [['documentMainFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'txt, csv, pdf, txt, doc, docx, xls, xlsx, rtf', 'maxFiles' => 1, 'on' => 'update'],
            [['documentMainFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt, csv, pdf, txt, doc, docx, xls, xlsx, rtf', 'maxFiles' => 1, 'on' => 'create'],
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'documentMainFile' => AmosDocumenti::t('amosdocumenti', '#MAIN_DOCUMENT'),
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DETAILS] = [
            'documentMainFile',
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione',
            'documenti_categorie_id',
            'data_pubblicazione',
            'data_rimozione',
            'comments_enabled',
            'status'
        ];
        $scenarios[self::SCENARIO_PUBLICATION] = [
            'destinatari_pubblicazione',
            'destinatari_notifiche'
        ];
        $scenarios[self::SCENARIO_SUMMARY] = [
            'status'
        ];
        return $scenarios;
    }
    
    /**
     * @inheritdoc
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
            'workflowLog' => [
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
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        
        $this->documentMainFile = $this->getDocumentMainFile()->one();
        $this->documentAttachments = $this->getDocumentAttachments()->one();
    }
    
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'titolo'
        ];
    }

    
    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
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
    public function getToValidateStatus()
    {
        return self::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE;
    }
    
    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
    }
    
    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::DOCUMENTI_WORKFLOW_STATUS_BOZZA;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'VALIDATORE_DOCUMENTI';
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
        
        if ($truncate) {
            $ret = $this->__shortText($this->descrizione, 200);
        }
        return $ret;
    }
    

    
    /**
     * @return array
     */
    public function getStatsToolbar()
    {
        $panels = [];
        $count_comments = 0;
        
        try {
            $panels = parent::getStatsToolbar();
            $filescount =  $this->getFileCount() - 1;
            $panels = ArrayHelper::merge($panels, StatsToolbarPanels::getDocumentsPanel($this, $filescount));
            if ($this->isCommentable()) {
                $commentModule = \Yii::$app->getModule('comments');
                if ($commentModule) {
                    /** @var \lispa\amos\comments\AmosComments $commentModule */
                    $count_comments = $commentModule->countComments($this);
                }
                $panels = ArrayHelper::merge($panels, StatsToolbarPanels::getCommentsPanel($this, $count_comments));
            }
        } catch (\Exception $ex) {
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

    /**
     * @return string The url to view of this model
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(["/".$this->getViewUrl(), "id" => $this->id]);
    }

    /**
     * @return mixed
     */
    public function getGrammar()
    {
        return new DocumentsGrammar();
    }
}
