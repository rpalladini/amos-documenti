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
use lispa\amos\core\helpers\T;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\models\DocumentiAllegati;
use lispa\amos\documenti\models\search\DocumentiAllegatiSearch;
use lispa\amos\upload\models\FilemanagerMediafile;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use lispa\amos\core\helpers\Html;
use lispa\amos\documenti\AmosDocumenti;

/**
 * DocumentiAllegatiController implements the CRUD actions for DocumentiAllegati model.
 */
class DocumentiAllegatiController extends CrudController
{
    /**
     * Init all view types
     *
     * @see    \yii\base\Object::init()    for more info.
     */
    public function init()
    {
        $this->setModelObj(new DocumentiAllegati());
        $this->setModelSearch(new DocumentiAllegatiSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosDocumenti::t('amosdocumenti', '{iconaTabella}'.Html::tag('p',AmosDocumenti::tHtml('amosdocumenti', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
    }

    /**
     * Lists all DocumentiAllegati models.
     *
     * @param   string $layout Layout in stringa
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single DocumentiAllegati model.
     * @param integer $filemanager_mediafile_id
     * @param integer $documenti_id
     * @param array $url
     * @return mixed
     */
    public function actionView($filemanager_mediafile_id, $documenti_id, $url = NULL)
    {
        $model = $this->findModelAllegati($filemanager_mediafile_id, $documenti_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!$url) {
                $url = ['/documenti/documenti/update', 'id' => $documenti_id, '#' => 'allegati'];
            }
            return $this->redirect($url);
        } else {
            if ($url == NULL) {
                $url = Yii::$app->urlManager->createUrl(['/documenti/documenti/update', 'id' => $documenti_id, '#' => 'allegati']);
            }
            return $this->render('view', ['model' => $model, 'url' => $url]);
        }
    }

    /**
     * Finds the DocumentiAllegati model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $filemanager_mediafile_id
     * @param integer $documenti_id
     * @return DocumentiAllegati the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelAllegati($filemanager_mediafile_id, $documenti_id)
    {
        if (($model = DocumentiAllegati::findOne(['filemanager_mediafile_id' => $filemanager_mediafile_id, 'documenti_id' => $documenti_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new DocumentiAllegati model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $idDocumenti ID del documento.
     * @param array $url
     * @return mixed
     */
    public function actionCreate($idDocumenti, $url = NULL)
    {
        $this->layout = "@vendor/lispa/amos-core/views/layouts/form";
        $model = new DocumentiAllegati;

        if ($model->load(Yii::$app->request->post())) {
            $modelFile = new FilemanagerMediafile();
            $modelFile->load($_FILES);
            $file = UploadedFile::getInstance($modelFile, 'file');
            if ($file) {
                $routes = Yii::$app->getModule('upload')->routes;
                $modelFile->saveUploadedFile($routes, true);
                $model->filemanager_mediafile_id = $modelFile->id;
                $model->documenti_id = $idDocumenti;

                $model->save(FALSE);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'idDocumenti' => $idDocumenti,
                ]);
            }
            //fine upload file
            if (!$url) {
                $url = ['/documenti/documenti/update', 'id' => $idDocumenti, '#' => 'allegati'];
            }
            return $this->redirect($url);
        } else {
            return $this->render('create', [
                'model' => $model,
                'idDocumenti' => $idDocumenti
            ]);
        }
    }

    /**
     * Deletes an existing DocumentiAllegati model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $filemanager_mediafile_id
     * @param integer $documenti_id
     * @param array $url
     * @return mixed
     */
    public function actionDelete($filemanager_mediafile_id, $documenti_id, $url = NULL)
    {
        $model = $this->findModelAllegati($filemanager_mediafile_id, $documenti_id);
        $documentiId = $model->documenti_id;
        $model->delete();
        if (!$url) {
            $url = ['/documenti/documenti/update', 'id' => $documentiId, '#' => 'allegati'];
        }
        return $this->redirect($url);
    }
}
