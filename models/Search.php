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
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Search model for auth items (roles and permissions).
 * 
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Search extends Model
{
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @var string
     */
    public $rule_name;
    
    /**
     * @var \dektrium\rbac\components\DbManager
     */
    protected $manager;
    
    /**
     * @var int
     */
    protected $type;

    /**
     * @inheritdoc
     */
    public function __construct($type, $config = [])
    {
        parent::__construct($config);
        $this->manager = \Yii::$app->authManager;
        $this->type    = $type;
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'default' => ['name', 'description', 'rule_name'],
        ];
    }
    
    /**
     * @param  array $params
     * @return ArrayDataProvider
     */
    public function search($params = [])
    {
        $dataProvider = \Yii::createObject(ArrayDataProvider::className());
        
        $query = (new Query)->select(['name', 'description', 'rule_name'])
                ->andWhere(['type' => $this->type])
                ->from($this->manager->itemTable);
        
        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'rule_name', $this->rule_name]);
        }
        
        $dataProvider->allModels = $query->all($this->manager->db);
        
        return $dataProvider;
    }

    /**
     * Returns list of item names.
     *
     * @return array
     */
    public function getNameList()
    {
        $rows = (new Query)
            ->select(['name'])
            ->andWhere(['type' => $this->type])
            ->from($this->manager->itemTable)
            ->all();

        return ArrayHelper::map($rows, 'name', 'name');
    }

    /**
     * Returns list of rule names.
     * 
     * @return array
     */
    public function getRuleList()
    {
        $rows = (new Query())
            ->select(['name'])
            ->from($this->manager->ruleTable)
            ->all();

        return ArrayHelper::map($rows, 'name', 'name');
    }
}
