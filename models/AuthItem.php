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
use yii\rbac\Item;
use dektrium\rbac\validators\RbacValidator;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
abstract class AuthItem extends Model
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $rule;

    /** @var string[] */
    public $children = [];

    /** @var \yii\rbac\Role|\yii\rbac\Permission */
    public $item;

    /** @var \dektrium\rbac\components\DbManager */
    protected $manager;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        $this->manager = \Yii::$app->authManager;
        if ($this->item instanceof Item) {
            $this->name        = $this->item->name;
            $this->description = $this->item->description;
            $this->children    = array_keys($this->manager->getChildren($this->item->name));
            if ($this->item->ruleName !== null) {
                $this->rule = get_class($this->manager->getRule($this->item->ruleName));
            }
        }
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('rbac', 'Name'),
            'description' => \Yii::t('rbac', 'Description'),
            'children' => \Yii::t('rbac', 'Children'),
            'rule' => \Yii::t('rbac', 'Rule'),
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'create' => ['name', 'description', 'children', 'rule'],
            'update' => ['name', 'description', 'children', 'rule'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'match', 'pattern' => '/^[\w-]+$/'],
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
                try {
                    $class = new \ReflectionClass($this->rule);
                } catch (\Exception $ex) {
                    $this->addError('rule', \Yii::t('rbac', 'Class "{0}" does not exist', $this->rule));
                    return;
                }

                if ($class->isInstantiable() == false) {
                    $this->addError('rule', \Yii::t('rbac', 'Rule class can not be instantiated'));
                }
                if ($class->isSubclassOf('\yii\rbac\Rule') == false) {
                    $this->addError('rule', \Yii::t('rbac', 'Rule class must extend "yii\rbac\Rule"'));
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

        if (!empty($this->rule)) {
            $rule = \Yii::createObject($this->rule);
            if (null === $this->manager->getRule($rule->name)) {
                $this->manager->add($rule);
            }
            $this->item->ruleName = $rule->name;
        } else {
            $this->item->ruleName = null;
        }
  
        if ($isNewItem) {
            \Yii::$app->session->setFlash('success', \Yii::t('rbac', 'Item has been created'));
            $this->manager->add($this->item);
        } else {
            \Yii::$app->session->setFlash('success', \Yii::t('rbac', 'Item has been updated'));
            $this->manager->update($oldName, $this->item);
        }

        $this->updateChildren();

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