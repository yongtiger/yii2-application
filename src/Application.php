<?php ///[Yii2 application]

/**
 * Yii2 application
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-application
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2017 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\application;

use Yii;
use yii\base\InvalidParamException;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class Application
 *
 * @package yongtiger\application
 */
class Application extends \yii\web\Application
{
    static $remoteAppConfigs = 'yongtiger.application.remoteAppConfigs';
    static $appClass = 'yongtiger\\application\\Application';

    /**
     * @inheritdoc
     */
    public function init() {
        $this->beforeInit();
        parent::init();
        $this->afterInit();
    }

    /**
     * Do some thing before application init().
     */
    public function beforeInit() {

    }

    /**
     * Do some thing after application init().
     */
    public function afterInit() {

    }

    /**
     * Call callback function with a remote application.
     *
     * @param string $appId
     * @param Closure $callback
     * @throws InvalidParamException|InvalidConfigException
     */
    public static function remoteAppCall($appId, \Closure $callback, \Closure $filterConfigCallback = null) {

        if ($callback === null || !$callback instanceof \Closure) {
            throw new InvalidParamException("Invalid param: `callback`.");
        }

        if ($appId !== Yii::$app->id && isset(Yii::$app->params[static::$remoteAppConfigs])) {

            if (!isset(Yii::$app->params[static::$remoteAppConfigs][$appId])) {
                throw new InvalidParamException("Invalid param: `appId`.");
            }
            if (!is_array(Yii::$app->params[static::$remoteAppConfigs][$appId])) {
                throw new InvalidConfigException("Invalid config: Yii::$app->params[" . static::$remoteAppConfigs . "][$appId].");
            }
            if (isset(Yii::$app->params[static::$remoteAppConfigs][$appId]['class'])) {
                $appClass = Yii::$app->params[static::$remoteAppConfigs][$appId]['class'];
                unset(Yii::$app->params[static::$remoteAppConfigs][$appId]['class']);
            } else {
                $appClass = static::$appClass;
            }

            // Save the original app to a temp app.
            $yiiApp = Yii::$app;
            ///[1.0.1 (fix:bug:i18n is invalid after Application::remoteAppCall('app-frontend'))]
            $yiiAliases = Yii::$aliases;
            $yiiClassMap = Yii::$classMap;
            $yiiContainer =Yii::$container;

            // Create empty config array.
            $config = [];

            // Assemble configuration for the current app.
            foreach (Yii::$app->params[static::$remoteAppConfigs][$appId] as $configPath) {
                // Merge every new configuration with the old config array.
                $config = ArrayHelper::merge($config, require (Yii::getAlias($configPath)));
            }

            // Call filter config callback function
            if ($filterConfigCallback !== null || $filterConfigCallback instanceof \Closure) {
                $config = call_user_func($filterConfigCallback, $config);
            }

            // Create a new app using the config array.
            $app = new $appClass($config); ///[v0.12.1 (UGD# replace component/application into yongtiger/appliaction)]

            // Call callback function by using the new app
            call_user_func($callback, $app);

            // Dump the new app
            unset($app);

            // Switch back to the original app.
            Yii::$app = $yiiApp;
            ///[1.0.1 (fix:bug:i18n is invalid after Application::remoteAppCall('app-frontend'))]
            Yii::$aliases = $yiiAliases;    
            Yii::$classMap = $yiiClassMap;
            Yii::$container = $yiiContainer;

            // Dump the temp app
            unset($yiiApp, $yiiAliases, $yiiClassMap, $yiiContainer);    ///[1.0.1 (fix:bug:i18n is invalid after Application::remoteAppCall('app-frontend'))]
        }

        call_user_func($callback, Yii::$app);
    }
}
