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

use lispa\amos\core\controllers\CrudController;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\helpers\T;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\dashboard\models\AmosUserDashboards;
use lispa\amos\dashboard\models\search\AmosUserDashboardsSearch;
use lispa\amos\dashboard\models\search\AmosWidgetsSearch;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\DocumentiCategorie;
use lispa\amos\documenti\models\search\DocumentiCategorieSearch;
use lispa\amos\upload\models\FilemanagerMediafile;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DocumentiCategorieController implements the CRUD actions for DocumentiCategorie model.
 */
class DocumentiCategorieController extends CrudController
{
    /*     * @var integer $slide * */
    public $slide = 1;

    /*     * @var $currentDashboard AmosUserDashboards * */
    public $currentDashboard;

    /**
     * Init all view types
     *
     * @see    \yii\base\Object::init()    for more info.
     */
    public function init()
    {
        $AmosUserDashboardsSearch = new AmosUserDashboardsSearch();

        $Dashboard = $AmosUserDashboardsSearch->current([
            'user_id' => Yii::$app->getUser()->getId(),
            'slide' => $this->getSlide(),
            'module' => $this->module->getUniqueId()
        ])->one();

        $this->setCurrentDashboard($Dashboard);

        $this->setModelObj(new DocumentiCategorie());
        $this->setModelSearch(new DocumentiCategorieSearch());

        $this->setAvailableViews([
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
     * @return int
     */
    public function getSlide()
    {
        return $this->slide;
    }

    /**
     * @param int $slide
     */
    public function setSlide($slide)
    {
        $this->slide = $slide;
    }

    /**
     * @return AmosUserDashboards
     */
    public function getCurrentDashboard()
    {
        if (!isset($this->currentDashboard)) {
            $this->initDashboard();
        }
        return $this->currentDashboard;
    }

    /**
     * @param AmosUserDashboards $currentDashboard
     */
    public function setCurrentDashboard($currentDashboard)
    {
        $this->currentDashboard = $currentDashboard;
    }

    private function initDashboard()
    {
        $AmosUserDashboardsSearch = new AmosUserDashboardsSearch();
        $params = [
            'user_id' => Yii::$app->getUser()->getId(),
            'slide' => $this->getSlide(),
            'module' => $this->module->getUniqueId()
        ];

        $Dashboard = $AmosUserDashboardsSearch->current($params)->one();

        if (!$Dashboard) {
            $Dashboard = new AmosUserDashboards($params);
            $Dashboard->save();

            if (Yii::$app->getModule('dashboard')->initHierarchyWidgets) {

                if ($Dashboard->isPrimary()) {

                    $AmosWidgetsQuery = AmosWidgetsSearch::selectable()
                        ->andWhere([
                            'child_of' => null
                        ]);
                } else {

                    $AmosWidgetsQuery = AmosWidgetsSearch::selectable()
                        ->andWhere(
                            ['is not', 'child_of', null]
                        )
                        ->andWhere(
                            [
                                'module' => $Dashboard->module
                            ]
                        );
                }
            } elseif (Yii::$app->getModule('dashboard')->initChildWidget) {
                if ($Dashboard->isPrimary()) {
                    $AmosWidgetsQuery = AmosWidgetsSearch::selectableIcon()
                        ->andWhere(
                            ['is not', 'child_of', null]
                        );
                    $AmosWidgetsQuery->union(AmosWidgetsSearch::selectableGraphic());
                } else {

                    $AmosWidgetsQuery = AmosWidgetsSearch::selectable()
                        ->andWhere(
                            ['is not', 'child_of', null]
                        )
                        ->andWhere(
                            [
                                'module' => $Dashboard->module
                            ]
                        );
                }
            }

            foreach ($AmosWidgetsQuery->all() as $widget) {
                $Dashboard->link('amosWidgetsClassnames', $widget);
            }
        }
        $this->setCurrentDashboard($Dashboard);
    }

    /**
     * Lists all DocumentiCategorie models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();

        $this->layout = "@vendor/lispa/amos-core/views/layouts/list";
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single DocumentiCategorie model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Finds the DocumentiCategorie model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DocumentiCategorie the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DocumentiCategorie::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new DocumentiCategorie model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = "@vendor/lispa/amos-core/views/layouts/form";
        $model = new DocumentiCategorie;

        if ($model->load(Yii::$app->request->post())) {
            $modelFile = new FilemanagerMediafile();
            $modelFile->load($_FILES);
            $file = UploadedFile::getInstance($modelFile, 'file');
            if ($file) {
                $routes = Yii::$app->getModule('upload')->routes;
                $modelFile->saveUploadedFile($routes, true);
                $model->filemanager_mediafile_id = $modelFile->id;
            }

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Categoria documenti salvata con successo.'));
                    return $this->redirect(['/documenti/documenti-categorie/update', 'id' => $model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DocumentiCategorie model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = "@vendor/lispa/amos-core/views/layouts/form";
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $modelFile = new FilemanagerMediafile();
            $modelFile->load($_FILES);
            $file = UploadedFile::getInstance($modelFile, 'file');
            if ($file) {
                $routes = Yii::$app->getModule('upload')->routes;
                $modelFile->saveUploadedFile($routes, true);
                $model->filemanager_mediafile_id = $modelFile->id;
            }

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Categoria documenti aggiornata con successo.'));
                    return $this->redirect(['/documenti/documenti-categorie/update', 'id' => $model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DocumentiCategorie model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
}
