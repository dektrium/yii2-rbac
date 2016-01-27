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

use Yii;
use yii\base\Module as BaseModule;
use yii\filters\AccessControl;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Module extends BaseModule
{
    /** @var bool Whether to show flash messages */
    public $enableFlashMessages = true;

    /** @var string */
    public $defaultRoute = 'role/index';
    
    /** @var array */
    public $admins = [];
	
	/** @var string The Administrator permission name. */
    public $adminPermission;
    
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $user = Yii::$app->user->identity;
                            if (method_exists($user, 'getIsAdmin')) {
                                return $user->getIsAdmin();
                            } else {
                                return (\Yii::$app->getAuthManager() && $this->adminPermission  ? \Yii::$app->user->can($this->adminPermission) : false) || in_array($this->username, $this->admins);
                            }
                        },
                    ]
                ],
            ],
        ];
    }
}
