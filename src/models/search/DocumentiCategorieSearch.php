<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 * @see http://example.com Developers'community
 * @license GPLv3
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3
 *
 * @package    lispa\amos\documenti\models\search
 * @category   CategoryName
 * @author     Lombardia Informatica S.p.A.
 */

namespace lispa\amos\documenti\models\search;

use lispa\amos\documenti\models\DocumentiCategorie;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DocumentiCategorieSearch represents the model behind the search form about `lispa\amos\documenti\models\DocumentiCategorie`.
 */
class DocumentiCategorieSearch extends DocumentiCategorie
{
    /**
     * @see    \yii\base\Model::rules()    for more info.
     */
    public function rules()
    {
        return [
            [['id', 'filemanager_mediafile_id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['titolo', 'sottotitolo', 'descrizione_breve', 'descrizione', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
     * Metodo search da utilizzare per recuperare le categorie di una documenti.
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DocumentiCategorie::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'filemanager_mediafile_id' => $this->filemanager_mediafile_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'titolo', $this->titolo])
            ->andFilterWhere(['like', 'sottotitolo', $this->sottotitolo])
            ->andFilterWhere(['like', 'descrizione_breve', $this->descrizione_breve])
            ->andFilterWhere(['like', 'descrizione', $this->descrizione]);

        return $dataProvider;
    }
}
