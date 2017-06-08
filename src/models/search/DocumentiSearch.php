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

namespace lispa\amos\documenti\models\search;

use lispa\amos\core\module\AmosModule;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\notificationmanager\models\NotificationChannels;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * DocumentiSearch represents the model behind the search form about `lispa\amos\documenti\models\Documenti`.
 */
class DocumentiSearch extends Documenti
{
    /**
     * @see    \yii\base\Model::rules()    for more info.
     */
    public function rules()
    {
        return [
            [['id', 'primo_piano', 'hits', 'abilita_pubblicazione', 'documenti_categorie_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['titolo', 'sottotitolo', 'descrizione_breve', 'descrizione', 'metakey', 'metadesc', 'data_pubblicazione', 'data_rimozione', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * @see    \yii\base\Model::scenarios()    for more info.
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @see    \yii\base\Component::behaviors()    for more info.
     */
    public function behaviors()
    {
        $parentBehaviors = parent::behaviors();

        $behaviors = [];
        //if the parent model News is a model enabled for tags, NewsSearch will have TaggableBehavior too
        $moduleTag = \Yii::$app->getModule('tag');
        if (isset($moduleTag) && in_array(Documenti::className(), $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
            $behaviors = ArrayHelper::merge($moduleTag->behaviors, $behaviors);
        }

        return ArrayHelper::merge($parentBehaviors, $behaviors);
    }

    /**
     * Documents search method
     *
     * @param array $params
     * @param string $queryType
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function search($params, $queryType, $limit = null)
    {
        $query = $this->buildQuery($queryType, $params);

        /** @var  $notify AmosNotify*/
        $notify = Yii::$app->getModule('notify');
        if($notify)
        {
            $notify->notificationOff(Yii::$app->getUser()->id, Documenti::className(),$query,NotificationChannels::CHANNEL_READ);
        }

        //set the data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $limit,
            ]
        ]);
        //check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort([
                'defaultOrder' => [
                    $this->orderAttribute => (int)$this->orderType
                ]
            ]);
        } else { //for widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC
                ]
            ]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (isset($params[$this->formName()]['tagValues'])) {

            $tagValues = $params[$this->formName()]['tagValues'];
            $this->setTagValues($tagValues);
            if (is_array($tagValues) && !empty($tagValues)) {
                $andWhere = "";
                $i = 0;
                foreach ($tagValues as $rootId => $tagId) {
                    if (!empty($tagId)) {
                        if ($i == 0) {
                            $query->innerJoin('entitys_tags_mm entities_tag',
                                "entities_tag.classname = '" . addslashes(Documenti::className()) . "' AND entities_tag.record_id=documenti.id");

                        }else{
                            $andWhere .= " OR ";
                        }
                        $andWhere .= "(entities_tag.tag_id in (" . $tagId . ") AND entities_tag.root_id = " . $rootId . " AND entities_tag.deleted_at is null)";
                        $i++;
                    }
                }
                $andWhere .= "";
                if(!empty($andWhere)) {
                    $query->andWhere($andWhere);
                }
            }
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'primo_piano' => $this->primo_piano,
            'hits' => $this->hits,
            'abilita_pubblicazione' => $this->abilita_pubblicazione,
            'data_pubblicazione' => $this->data_pubblicazione,
            'data_rimozione' => $this->data_rimozione,
            'documenti_categorie_id' => $this->documenti_categorie_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'titolo', $this->titolo])
            ->andFilterWhere(['like', 'sottotitolo', $this->sottotitolo])
            ->andFilterWhere(['like', 'descrizione_breve', $this->descrizione_breve])
            ->andFilterWhere(['like', 'descrizione', $this->descrizione])
            ->andFilterWhere(['like', 'metakey', $this->metakey])
            ->andFilterWhere(['like', 'metadesc', $this->metadesc]);

        return $dataProvider;
    }

    /**
     * Documents base search: all documents matching search parameters and not deleted.
     *
     * @param   array $params Search parameters
     * @return \yii\db\ActiveQuery
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);

        return Documenti::find()->distinct();
    }

    /**
     * Search the Documents created by the logged user
     *
     * @param array $params Array di parametri per la ricerca
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchOwnDocuments($params, $limit = null)
    {
        return $this->search($params, 'created-by', $limit);
    }

    /**
     * Ritorna solamente $this.
     *
     * @return $this
     */
    public function validazioneAbilitata()
    {
        return $this;
    }

    /**
     * Search documents to validate based on cwh rules if cwh is active, all documents in 'to validate status' otherwise
     *
     * @param array $params Array di parametri per la ricerca
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchToValidateDocuments($params, $limit = null)
    {
        return $this->search($params, 'to-validate', $limit);
    }

    /**
     * Search last documents in validated status, generally the limit is set to 3 (by last documents graphic widget)
     *
     * @param array $params Array of search parameters
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function lastDocuments($params, $limit = null)
    {
        $dataProvider = $this->searchAll(Yii::$app->request->getQueryParams(), $limit);

        return $dataProvider;
    }

    /**
     * Search all validated documents
     *
     * @param array $params Array of get parameters for search
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function searchAll($params, $limit = null)
    {
        return $this->search($params, 'all', $limit);
    }

    /**
     * Search method useful for retrieve all validated documenti (based on publication rule and visibility).
     *
     * @param array $params Array of get parameters for search
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function searchOwnInterest($params, $limit = null)
    {
        return $this->search($params, 'own-interest', $limit);
    }

    /**
     * Search method useful for retrieve all non-deleted documenti.
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHighlightedAndHomepageDocumenti($params)
    {
        $query = $this->highlightedAndHomepageDocumentiQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    public function highlightedAndHomepageDocumentiQuery($params)
    {
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->where([])
            ->andWhere([
                $tableName . '.status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
            ])
            ->andWhere($tableName . '.deleted_at IS NULL')
            ->andWhere($tableName . '.in_evidenza = 1')
            ->andWhere($tableName . '.primo_piano = 1');
        return $query;
    }

    /**
     * @param string $queyType
     * @param array $params
     * @return ActiveQuery $query
     */
    public function buildQuery($queyType, $params){

        $query = $this->baseSearch($params);
        $classname = Documenti::className();
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;

        if (isset($moduleCwh)) {
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new \lispa\amos\cwh\query\CwhActiveQuery(
                $classname,[
                'queryBase' => $query
            ]);
        }
        $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
        switch($queyType){
            case 'created-by':
                if ($isSetCwh){
                    $query = $cwhActiveQuery->getQueryCwhOwn();
                } else {
                    $query->andFilterWhere([
                        'created_by' => Yii::$app->getUser()->id
                    ]);
                }
                break;
            case 'all':
                if ($isSetCwh){
                    $query = $cwhActiveQuery->getQueryCwhAll();
                } else {
                    $query->andWhere([
                        'status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
                    ]);
                }
                break;
            case'to-validate':
                if ($isSetCwh){
                    $query = $cwhActiveQuery->getQueryCwhToValidate();
                } else {
                    $query->andWhere([
                        'status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE
                    ]);
                }
                break;
            case 'own-interest':
                if ($isSetCwh){
                    $query = $cwhActiveQuery->getQueryCwhOwnInterest();
                } else {
                    $query->andWhere([
                        'status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
                    ]);
                }
                break;
        }
        return $query;
    }

    /**
     * @param AmosModule $moduleCwh
     * @param string $classname
     * @return bool
     */
    private function isSetCwh($moduleCwh, $classname){
        if (isset($moduleCwh) && in_array($classname, $moduleCwh->modelsEnabled) ) {
            return true;
        } else {
            return false;
        }
    }
}
