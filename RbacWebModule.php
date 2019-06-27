<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace dektrium\rbac;

use yii\base\Module as BaseModule;
use yii\filters\AccessControl;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RbacWebModule extends BaseModule
{
    const EVENT_INIT = 'init';
    const EVENT_MENU = 'menu';
    const EVENT_PERMISSION_FORM = 'permissionForm';
    
    const MODEL_ASSIGNMENT = 100;
    const MODEL_AUTHITEM = 101;
    const MODEL_PERMISSION = 102;
    const MODEL_ROLE = 103;
    const MODEL_RULE = 104;
    const MODEL_RULE_SEARCH = 105;
    const MODEL_SEARCH = 106;
    
    /**
     * @var string
     */
    public $defaultRoute = 'role/index';
    
    /**
     * @var array
     */
    public $admins = [];
	
    /**
     * @var string The Administrator permission name.
     */
    public $adminPermission;
    
     /**
     * @var array the model settings for the module. The keys will be one of the `self::MODEL_` constants
     * and the value will be the model class names you wish to set.
     *
     * @see `setConfig()` method for the default settings
     */
    public $modelSettings = [];
    
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'         => true,
                        'roles'         => ['@'],
                        'matchCallback' => [$this, 'checkAccess'],
                    ]
                ],
            ],
        ];
    }
    
    public function init()
    {
        parent::init();
        $this->setConfig();
        $this->trigger(self::EVENT_INIT);
    }
    
    public function setConfig()
    {
        $this->modelSettings = array_replace_recursive([
            self::MODEL_ASSIGNMENT => 'dektrium\rbac\models\Assignment',
            self::MODEL_AUTHITEM => 'dektrium\rbac\models\AuthItem',
            self::MODEL_PERMISSION => 'dektrium\rbac\models\Permission',
            self::MODEL_ROLE => 'dektrium\rbac\models\Role',
            self::MODEL_RULE => 'dektrium\rbac\models\Rule',
            self::MODEL_RULE_SEARCH => 'dektrium\rbac\models\RuleSearch',
            self::MODEL_SEARCH => 'dektrium\rbac\models\Search',
        ], $this->modelSettings);
    }

    /**
     * Checks access.
     *
     * @return bool
     */
    public function checkAccess()
    {
        $user = \Yii::$app->user->identity;

        if (method_exists($user, 'getIsAdmin')) {
            return $user->getIsAdmin();
        } else if ($this->adminPermission) {
            return \Yii::$app->user->can($this->adminPermission);
        } else {
            return isset($user->username) ? in_array($user->username, $this->admins) : false;
        }
    }
    
    public function getModelClass($m)
    {
        return $this->modelSettings[$m];
    }
}
