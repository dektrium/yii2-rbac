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

use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\helpers\Json;
use yii\rbac\Item;
use dektrium\rbac\validators\RbacValidator;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
abstract class AuthItem extends Model
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
    public $rule;

    /**
     * @var string
     */
    public $data;

    /**
     * @var bool
     */
    public $dataCannotBeDecoded = false;

    /**
     * @var string[]
     */
    public $children = [];

    /**
     * @var \yii\rbac\Role|\yii\rbac\Permission
     */
    public $item;

    /**
     * @var \dektrium\rbac\components\DbManager
     */
    protected $manager;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->manager = \Yii::$app->authManager;
        if ($this->item instanceof Item) {
            $this->name        = $this->item->name;
            $this->description = $this->item->description;
            $this->children    = array_keys($this->manager->getChildren($this->item->name));

            try {
                if (is_object($this->item->data)) {
                    $this->dataCannotBeDecoded = true;
                } else if ($this->item->data !== null) {
                    $this->data = Json::encode($this->item->data);
                }
            } catch (InvalidParamException $e) {
                $this->dataCannotBeDecoded = true;
            }

            if ($this->item->ruleName !== null) {
                $this->rule = get_class($this->manager->getRule($this->item->ruleName));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('rbac', 'Name'),
            'description' => \Yii::t('rbac', 'Description'),
            'children' => \Yii::t('rbac', 'Children'),
            'rule' => \Yii::t('rbac', 'Rule'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'create' => ['name', 'description', 'children', 'rule', 'data'],
            'update' => ['name', 'description', 'children', 'rule', 'data'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['name', 'description', 'rule'], 'trim'],
            ['name', function () {
                if ($this->manager->getItem($this->name) !== null) {
                    $this->addError('name', \Yii::t('rbac', 'Auth item with such name already exists'));
                }
            }, 'when' => function () {
                return $this->scenario == 'create' || $this->item->name != $this->name;
            }],
            ['children', RbacValidator::className()],
            ['rule', function () {
                $rule = $this->manager->getRule($this->rule);

                if (!$rule) {
                    $this->addError('rule', \Yii::t('rbac', 'Rule {0} does not exist', $this->rule));
                }
            }],
            ['data', function () {
                try {
                    Json::decode($this->data);
                } catch (InvalidParamException $e) {
                    $this->addError('data', \Yii::t('rbac', 'Data must be type of JSON ({0})', $e->getMessage()));
                }
            }],
        ];
    }

    /**
     * Saves item.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate() == false) {
            return false;
        }

        if ($isNewItem = ($this->item === null)) {
            $this->item = $this->createItem($this->name);
        } else {
            $oldName = $this->item->name;
        }

        $this->item->name        = $this->name;
        $this->item->description = $this->description;
        $this->item->data        = $this->data == null ? null : Json::decode($this->data);
        $this->item->ruleName    = empty($this->rule) ? null : $this->rule;
  
        if ($isNewItem) {
            \Yii::$app->session->setFlash('success', \Yii::t('rbac', 'Item has been created'));
            $this->manager->add($this->item);
        } else {
            \Yii::$app->session->setFlash('success', \Yii::t('rbac', 'Item has been updated'));
            $this->manager->update($oldName, $this->item);
        }

        $this->updateChildren();

        $this->manager->invalidateCache();

        return true;
    }

    /**
     * Updated items children.
     */
    protected function updateChildren()
    {
        $children = $this->manager->getChildren($this->item->name);
        $childrenNames = array_keys($children);

        if (is_array($this->children)) {
            // remove children that
            foreach (array_diff($childrenNames, $this->children) as $item) {
                $this->manager->removeChild($this->item, $children[$item]);
            }
            // add new children
            foreach (array_diff($this->children, $childrenNames) as $item) {
                $this->manager->addChild($this->item, $this->manager->getItem($item));
            }
        } else {
            $this->manager->removeChildren($this->item);
        }
    }

    /**
     * @return array An array of unassigned items.
     */
    abstract public function getUnassignedItems();

    /**
     * @param  string         $name
     * @return \yii\rbac\Item
     */
    abstract protected function createItem($name);
}
