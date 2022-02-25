<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dektrium\rbac;

use dektrium\rbac\components\DbManager;
use dektrium\rbac\components\ManagerInterface;
use dektrium\user\Module as UserModule;
use yii\base\Application;
use yii\web\Application as WebApplication;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;

/**
 * Bootstrap class registers translations and needed application components.
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    const VERSION = '1.0.0-alpha';

    /** @inheritdoc */
    public function bootstrap($app)
    {
        // register translations
        if (!isset($app->get('i18n')->translations['rbac*'])) {
            $app->get('i18n')->translations['rbac*'] = [
                'class'    => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
            ];
        }

        if ($this->checkRbacModuleInstalled($app)) {
            $authManager = $app->get('authManager', false);

            if (!$authManager) {
                $app->set('authManager', [
                    'class' => DbManager::className(),
                ]);
            } else if (!($authManager instanceof ManagerInterface)) {
                throw new InvalidConfigException('You have wrong authManager configuration');
            }

            // if dektrium/user extension is installed, copy admin list from there
            if ($this->checkUserModuleInstalled($app) && $app instanceof WebApplication) {
                $app->getModule('rbac')->admins = $app->getModule('user')->admins;
            }   
        }
    }
    
    /**
     * Verifies that dektrium/yii2-rbac is installed and configured.
     * @param  Application $app
     * @return bool
     */
    protected function checkRbacModuleInstalled(Application $app)
    {
        if ($app instanceof WebApplication) {
            return $app->hasModule('rbac') && $app->getModule('rbac') instanceof RbacWebModule;
        } else {
            return $app->hasModule('rbac') && $app->getModule('rbac') instanceof RbacConsoleModule;
        }
    }
    
    /**
     * Verifies that dektrium/yii2-user is installed and configured.
     * @param  Application $app
     * @return bool
     */
    protected function checkUserModuleInstalled(Application $app)
    {
        return $app->hasModule('user') && $app->getModule('user') instanceof UserModule;
    }
    
    /**
     * Verifies that authManager component is configured.
     * @param  Application $app
     * @return bool
     */
    protected function checkAuthManagerConfigured(Application $app)
    {
        return $app->authManager instanceof ManagerInterface;
    }
}
