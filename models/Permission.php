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

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Permission extends Model
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $rule;

    /** @var \yii\rbac\Permission */
    public $item;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        if ($this->item instanceof \yii\rbac\Permission) {
            $this->name        = $this->item->name;
            $this->description = $this->item->description;
        }
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'create' => ['name', 'description', 'rule'],
            'update' => ['name', 'description', 'rule'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['name', 'rule'], 'match', 'pattern' => '/^[\w-]+$/'],
            [['name', 'description', 'rule'], 'trim'],
            ['name', function () {
                if (\Yii::$app->authManager->getPermission($this->name) !== null) {
                    $this->addError('name', \Yii::t('rbac', 'Permission with such name already exists'));
                }
            }, 'when' => function () {
                return $this->scenario == 'create' || $this->item->name != $this->name;
            }],
            ['rule', function () {
                if (\Yii::$app->authManager->getRule($this->rule) === null) {
                    $this->addError('rule', \Yii::t('rbac', 'There is no rule with such name'));
                }
            }]
        ];
    }

    /**
     * Saves permission.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate() == false) {
            return false;
        }

        $manager = \Yii::$app->authManager;

        if ($isNewItem = ($this->item === null)) {
            $this->item = $manager->createPermission($this->name);
        } else {
            $oldName = $this->item->name;
        }

        $this->item->name        = $this->name;
        $this->item->description = $this->description;

        // TODO: add rules management
        // TODO: add parent management

        if ($isNewItem) {
            \Yii::$app->session->setFlash('success', \Yii::t('rbac', 'Permission has been created'));
            $manager->add($this->item);
        } else {
            \Yii::$app->session->setFlash('success', \Yii::t('rbac', 'Permission has been updated'));
            $manager->update($oldName, $this->item);
        }

        return true;
    }
}