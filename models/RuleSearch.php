<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace dektrium\rbac\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;

/**
 * Search model for rules.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RuleSearch extends Rule
{
    /**
     * @var string
     */
    public $created_at;

    /**
     * @return array
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'string'],
        ];
    }

    /**
     * @param  array $params
     * @return ArrayDataProvider
     */
    public function search(array $params = [])
    {
        $query = (new Query())
            ->select(['name', 'data', 'created_at', 'updated_at'])
            ->from($this->authManager->ruleTable)
            ->orderBy(['name' => SORT_ASC]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
        }

        $query->andFilterWhere(['name' => $this->name]);

        return \Yii::createObject([
            'class' => ActiveDataProvider::className(),
            'query' => $query,
            'db'    => $this->authManager->db,
            'sort'  => [
                'attributes' => ['name', 'created_at', 'updated_at'],
            ],
        ]);
    }

    /**
     * @param  string|null $searchQuery
     * @return array
     */
    public function getRuleNames($searchQuery = null)
    {
        $query = (new Query())
            ->select(['id' => 'name', 'text' => 'name'])
            ->from($this->authManager->ruleTable)
            ->orderBy(['name' => SORT_ASC])
            ->limit(10);
        
        if ($searchQuery) {
            $query->where(['LIKE', 'LOWER(name)', mb_strtolower($searchQuery)]);
        }

        return $query->all();
    }
}
