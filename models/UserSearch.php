<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;
use yii\db\QueryInterface;

/**
 * PersonSearch represents the model behind the search form about `app\models\Person`.
 */
class UserSearch extends User
{
    /* Вычисляемые аттрибуты */
    public $organizationName;
    public $filePath;
    public $document;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'organization_id'], 'integer'],
            [['username', 'organizationName','filePath', 'document'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /* Включаем связанные таблицы в выборку */
        $query->joinWith(['organization', 'file', 'document']);

        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'username' => [
                    'asc' => ['username' => SORT_ASC],
                    'desc' => ['username' => SORT_DESC],
                    'label' => 'Имя пользователя',
                    'default' => SORT_ASC
                ],

                'organizationName' => [
                    'asc' => ['organization.name' => SORT_ASC],
                    'desc' => ['organization.name' => SORT_DESC],
                    'label' => 'Название организации'
                ],
                'organization_id',
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'organization_id' => $this->organization_id,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username]);


        // Фильтр по организации
        if ($this->organizationName) {
            $query->andFilterWhere(['like', 'organization.name', $this->organizationName]);
        }

        return $dataProvider;
    }

    protected function addCondition(QueryInterface $query, $attribute, $partialMatch = false)
    {
        if (($pos = strrpos($attribute, '.')) !== false) {
            $modelAttribute = substr($attribute, $pos + 1);
        } else {
            $modelAttribute = $attribute;
        }

        $value = $this->$modelAttribute;
        if (trim($value) === '') {
            return;
        }

        /*
         * Для корректной работы фильтра со связью со
         * свой же моделью делаем:
         */
        $attribute = "user.$attribute";

        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}
