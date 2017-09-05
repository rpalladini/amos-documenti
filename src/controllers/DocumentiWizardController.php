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

use lispa\amos\core\behaviors\TaggableBehavior;
use lispa\amos\core\controllers\CrudController;
use lispa\amos\core\module\BaseAmosModule;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\components\PartsWizardDocumentiCreation;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\models\search\DocumentiSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class DocumentiWizardController
 *
 * @property \lispa\amos\documenti\models\Documenti $model
 *
 * @package lispa\amos\documenti\controllers
 */
class DocumentiWizardController extends CrudController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModelObj(new Documenti());
        $this->setModelSearch(new DocumentiSearch());
        $this->setAvailableViews([]);
        
        parent::init();
        
        $this->layout = '@vendor/lispa/amos-core/views/layouts/progress_wizard';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'introduction',
                            'details',
                            'publication',
                            'summary',
                            'finish'
                        ],
                        'roles' => ['CREATORE_DOCUMENTI']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }
    
    /**
     * Set view params for the creation wizard.
     */
    private function setParamsForView()
    {
        $parts = new PartsWizardDocumentiCreation(['model' => $this->model]);
        Yii::$app->view->title = $parts->active['index'] . '. ' . $parts->active['label'];
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => Yii::$app->view->title]
        ];
        Yii::$app->view->params['model'] = $this->model;
        Yii::$app->view->params['partsQuestionario'] = $parts;
        Yii::$app->view->params['hideBreadcrumb'] = true; // This param hide the wizard Breadcrumb.
        Yii::$app->view->params['textHelp'] = [
            'filename' => 'documents-description'
        ];
    }
    
    /**
     * Get the next action to go to.
     * @return \yii\web\Response
     */
    public function goToNextPart()
    {
        $parts = new PartsWizardDocumentiCreation(['model' => $this->model]);
        return $this->redirect([$parts->getNext(), 'id' => $this->model->id]);
    }
    
    /**
     * Action for introduction step of the wizard.
     * @param int|null $id The document id.
     * @return string|\yii\web\Response
     */
    public function actionIntroduction($id = null)
    {
        Url::remember();
        
        if (isset($id)) {
            $this->model = $this->findModel($id);
        } else {
            $this->model = new Documenti();
        }
        
        $cwhBehavior = $this->model->getBehavior('cwhBehavior');
        if (!empty($cwhBehavior)) {
            $this->model->detachBehavior('cwhBehavior');
        }
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }
        $this->model->setScenario(Documenti::SCENARIO_INTRODUCTION);
        
        if (Yii::$app->getRequest()->post()) {
            return $this->goToNextPart();
        }
        
        $this->setParamsForView();
        return $this->render('introduction', [
            'model' => $this->model
        ]);
    }
    
    /**
     * Action for details step of the wizard.
     * @param int|null $id The document id.
     * @return string|\yii\web\Response
     */
    public function actionDetails($id = null)
    {
        Url::remember();
        
        if (isset($id)) {
            $this->model = $this->findModel($id);
            if (!($this->model->created_by == Yii::$app->user->id || Yii::$app->user->can('DocumentValidate',
                    ['model' => $this->model]))
            ) {
                Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
                return $this->redirect('/documenti/documenti-wizard/introduction');
            }
            $this->model->setScenario(Documenti::SCENARIO_DETAILS);
        } else {
            $this->model = new Documenti(['scenario' => Documenti::SCENARIO_DETAILS]);
        }
        $cwhBehavior = $this->model->getBehavior('cwhBehavior');
        if (!empty($cwhBehavior)) {
            $this->model->detachBehavior('cwhBehavior');
        }
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }
        
        $this->setParamsForView();
        return $this->render('details', [
            'model' => $this->model
        ]);
    }
    
    /**
     * Action for publication step of the wizard.
     * @param int $id The document id.
     * @return string|\yii\web\Response
     */
    public function actionPublication($id)
    {
        Url::remember();
        
        $this->model = $this->findModel($id);
        if (!($this->model->created_by == Yii::$app->user->id || Yii::$app->user->can('DocumentValidate',
                ['model' => $this->model]))
        ) {
            Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
            return $this->redirect('/documenti/documenti-wizard/introduction');
        }
        $this->model->setScenario(Documenti::SCENARIO_PUBLICATION);
        
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }
        
        $this->setParamsForView();
        return $this->render('publication', [
            'model' => $this->model
        ]);
    }
    
    /**
     * Action for summary step of the wizard.
     * @param int $id The discussion id.
     * @return string|\yii\web\Response
     */
    public function actionSummary($id)
    {
        Url::remember();
        
        $this->model = $this->findModel($id);
        
        if (!($this->model->created_by == Yii::$app->user->id || Yii::$app->user->can('DocumentValidate', ['model' => $this->model]))) {
            Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
            return $this->redirect('/documenti/documenti-wizard/introduction');
        }
        
        $this->model->setScenario(Documenti::SCENARIO_SUMMARY);
        
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post())) {
            $cwhBehavior = $this->model->getBehavior('cwhBehavior');
            if (!empty($cwhBehavior)) {
                $this->model->detachBehavior('cwhBehavior');
            }
            $this->model->detachBehaviorByClassName(TaggableBehavior::className());
            if ($this->model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
                $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE;
                $this->model->save();
                $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
            }
            if ($this->model->save()) {
                return $this->goToNextPart();
            }
            if (!empty($cwhBehavior)) {
                $this->model->attachBehavior('cwhBehavior', $cwhBehavior);
            }
        }
        
        $viewPublishId = 'request-publish-btn';
        $viewPublishLabel = 'Request publish';
        
        $loggedUser = Yii::$app->getUser();
        
        if ($loggedUser->can('DocumentValidate', ['model' => $this->model])) {
            $viewPublishId = 'publish-btn';
            $viewPublishLabel = 'Publish';
        }
        
        $this->setParamsForView();
        return $this->render('summary', [
            'model' => $this->model,
            'viewPublishId' => $viewPublishId,
            'viewPublishLabel' => $viewPublishLabel
        ]);
    }
    
    /**
     * Action for finish step of the wizard.
     * @param int $id The discussion id.
     * @return string
     */
    public function actionFinish($id)
    {
        Url::remember();
        
        $this->model = $this->findModel($id);
        $finishMessage = AmosDocumenti::tHtml('amosdocumenti', '#THE_DOCUMENT_HAS_BEEN') . ' ';
        $loggedUser = Yii::$app->getUser();
        if ($loggedUser->can('DocumentValidate', ['model' => $this->model]) && $this->model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
            $finishMessage .= AmosDocumenti::tHtml('amosdocumenti', '#PUBLISHED');
        } else {
            if (!($this->model->created_by == Yii::$app->user->id)) {
                Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
                return $this->redirect('/documenti/documenti-wizard/introduction');
            }
            $finishMessage .= AmosDocumenti::tHtml('amosdocumenti', '#CREATED_WAITING_PUBLISH');
        }
        $this->setParamsForView();
        Yii::$app->view->params['hidePartsUrl'] = true; // This param disable the progress wizard menu links.
        return $this->render('finish', [
            'model' => $this->model,
            'finishMessage' => $finishMessage
        ]);
    }
}
