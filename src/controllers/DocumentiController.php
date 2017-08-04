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

namespace lispa\amos\documenti\controllers;

use lispa\amos\core\controllers\CrudController;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\helpers\T;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\cwh\AmosCwh;
use lispa\amos\dashboard\controllers\TabDashboardControllerTrait;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\models\search\DocumentiSearch;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class DocumentiController
 * @package lispa\amos\documenti\controllers
 */
class DocumentiController extends CrudController
{
    /**
     * Uso il trait per inizializzare la dashboard a tab
     */
    use TabDashboardControllerTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new Documenti());
        $this->setModelSearch(new DocumentiSearch());

        $this->setAvailableViews([
            'list' => [
                'name' => 'list',
                'label' => AmosDocumenti::t('amosdocumenti', '{iconaLista}'.Html::tag('p',AmosDocumenti::tHtml('amosdocumenti', 'Lista')), [
                    'iconaLista' => AmosIcons::show('view-list')
                ]),
                'url' => '?currentView=list'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosDocumenti::t('amosdocumenti', '{iconaTabella}'.Html::tag('p',AmosDocumenti::tHtml('amosdocumenti', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /* 'map' => [
              'name' => 'map',
              'label' => AmosDocumenti::t('amosdocumenti', '{iconaMappa}'.Html::tag('p',AmosDocumenti::tHtml('amosdocumenti', 'Mappa')), [
              'iconaMappa' => AmosIcons::show('map-alt')
              ]),
              'url' => '?currentView=map'
              ], */
        ]);

        parent::init();
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
                            'download-documento-principale',
                            'download',
                            'index',
                            'documenti',
                            'all-documents',
                            'own-interest-documents'
                        ],
                        'roles' => ['LETTORE_DOCUMENTI', 'AMMINISTRATORE_DOCUMENTI', 'CREATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'own-documents',
                        ],
                        'roles' => ['CREATORE_DOCUMENTI', 'AMMINISTRATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI' ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'validate-document',
                            'reject-document',
                        ],
                        'roles' => ['AMMINISTRATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI', 'FACILITATOR', 'DocumentValidateOnDomain']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'to-validate-documents'
                        ],
                        'roles' => ['VALIDATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI', 'AMMINISTRATORE_DOCUMENTI', 'DocumentValidateOnDomain']
                    ],
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
     * @see \yii\base\Controller::actions()    for more info.
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionValidateDocument($id)
    {
        $news = Documenti::findOne($id);
        try {
            $news->sendToStatus(Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO);
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', AmosDocumenti::t('amosnews', $e->getMessage()));
            return $this->redirect(Url::previous());
        }
        $news->save(false);
        Yii::$app->session->addFlash('success', AmosDocumenti::t('amosnews', 'Document validated!'));
        return $this->redirect(Url::previous());
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRejectDocument($id)
    {
        $news = Documenti::findOne($id);
        try {
            $news->sendToStatus(Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA);
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', AmosDocumenti::t('amosnews', $e->getMessage()));
            return $this->redirect(Url::previous());
        }
        $news->save(false);
        Yii::$app->session->addFlash('success', AmosDocumenti::t('amosnews', 'Document rejected!'));
        return $this->redirect(Url::previous());
    }

    /**
     * Lists all Documenti models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Tutti i documenti'));
        $this->setCreateNewBtnLabel();

        $this->layout = "@vendor/lispa/amos-core/views/layouts/list";
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Used for set page title and breadcrumbs.
     *
     * @param string $documentiPageTitle Documenti page title (ie. Created by documenti, ...)
     */
    private function setTitleAndBreadcrumbs($documentiPageTitle)
    {
        $this->setNetworkDashboardBreadcrumb();
        Yii::$app->session->set('previousTitle', $documentiPageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $documentiPageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $documentiPageTitle];
    }

    public function setNetworkDashboardBreadcrumb()
    {
        /** @var AmosCwh $moduleCwh */
        $moduleCwh = Yii::$app->getModule('cwh');
        $scope = $moduleCwh->getCwhScope();
        if (isset($scope)) {
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
                $community = \lispa\amos\community\models\Community::findOne($communityId);
                $dashboardCommunityTitle = AmosDocumenti::t('amosdocumenti',"Dashboard"). ' '.$community->name;
                $dasbboardCommunityUrl = Yii::$app->urlManager->createUrl(['community/join', 'id' => $communityId]);
                Yii::$app->view->params['breadcrumbs'][] = ['label' => $dashboardCommunityTitle, 'url' => $dasbboardCommunityUrl];
            }
        }
    }

    /**
     * Set a view param used in \lispa\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosDocumenti::tHtml('amosdocumenti', 'Aggiungi nuovo documento')
        ];
    }

    /**
     * Displays a single Documenti model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'idDocumenti' => $id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new Documenti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = "@vendor/lispa/amos-core/views/layouts/form";
        $model = new Documenti;
        $this->model = $model;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Documenti salvata con successo.'));
                    return $this->redirect(['/documenti/documenti/update', 'id' => $model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Documenti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        Url::remember();

        $this->layout = "@vendor/lispa/amos-core/views/layouts/form";
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Documento aggiornato con successo.'));
                    return $this->redirect(['/documenti/documenti/update', 'id' => $model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Private method to download a file.
     *
     * @param string $path A path to a file.
     * @param string $file A filename
     * @param array $extensions
     * @param string $titolo
     * @return bool
     */
    private function downloadFile($path, $file, $extensions = [], $titolo = null)
    {
        if (is_file($path)) {
            $file_info = pathinfo($path);
            $extension = $file_info["extension"];

            if (is_array($extensions)) {
                foreach ($extensions as $e) {
                    if ($e === $extension) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        $titolo = $titolo ? $titolo : 'Allegato_documenti';
                        header('Content-Disposition: attachment; filename=' . $titolo . '.' . $extension);
                        header('Content-Transfer-Encoding: binary');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($path));
                        readfile($path);
                        ob_clean();
                        flush();

                        return true; //Yii::$app->response->sendFile($path);
                    }
                }
            }
        }
        return false;
    }

    /**
     * Deletes an existing Documenti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        if (!$model->getErrors()) {
            Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Documento cancellato correttamente.'));
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Non sei autorizzato a cancellare il documento.'));
        }
        return $this->redirect(Url::previous());
    }

    /**
     * Action to search only for own documents
     *
     * @return string
     */
    public function actionOwnDocuments ()
    {
        Url::remember();
        $dataProvider = $this->getModelSearch()->searchOwnDocuments(Yii::$app->request->getQueryParams());
        $this->setDataProvider($dataProvider);
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Documenti creati da me'));
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosDocumenti::t('amosdocumenti', '{iconaTabella}'.Html::tag('p',AmosDocumenti::tHtml('amosdocumenti', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setCreateNewBtnLabel();

        $this->layout = "@vendor/lispa/amos-core/views/layouts/list";
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Action to search only for own interest documents
     *
     * @return string
     */
    public function actionOwnInterestDocuments ($currentView = null)
    {
        Url::remember();
        if(empty($currentView)){
            $currentView = 'list';
        }
        $dataProvider = $this->getModelSearch()->searchOwnInterest(Yii::$app->request->getQueryParams());
        $this->setDataProvider($dataProvider);
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Documenti di mio interesse'));
        $this->setCurrentView($this->getAvailableView($currentView));
        $this->setCreateNewBtnLabel();

        $this->layout = "@vendor/lispa/amos-core/views/layouts/list";
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Action to search to validate documenti.
     *
     * @return string
     */
    public function actionToValidateDocuments()
    {
        Url::remember();
        $dataProvider = $this->getModelSearch()->searchToValidateDocuments(Yii::$app->request->getQueryParams());
        $this->setDataProvider($dataProvider);
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Documenti da validare'));

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosDocumenti::t('amosdocumenti', '{iconaTabella}'.Html::tag('p',AmosDocumenti::tHtml('amosdocumenti', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setCreateNewBtnLabel();

        $this->layout = "@vendor/lispa/amos-core/views/layouts/list";
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Action for search all documenti.
     *
     * @return string
     */
    public function actionAllDocuments($currentView = null)
    {
        Url::remember();
        if(empty($currentView)){
            $currentView = 'list';
        }
        $dataProvider = $this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams());
        $this->setDataProvider($dataProvider);
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Tutti i documenti'));
        $this->setCurrentView($this->getAvailableView($currentView));
        $this->setCreateNewBtnLabel();

        $this->layout = "@vendor/lispa/amos-core/views/layouts/list";
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }
}
