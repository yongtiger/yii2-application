# Yii2-application releas version 1.0.1 (fix:bug:i18n is invalid after Application::remoteAppCall('app-frontend'))

[![Latest Stable Version](https://poser.pugx.org/yongtiger/yii2-application/v/stable)](https://packagist.org/packages/yongtiger/yii2-application)
[![Total Downloads](https://poser.pugx.org/yongtiger/yii2-application/downloads)](https://packagist.org/packages/yongtiger/yii2-application) 
[![Latest Unstable Version](https://poser.pugx.org/yongtiger/yii2-application/v/unstable)](https://packagist.org/packages/yongtiger/yii2-application)
[![License](https://poser.pugx.org/yongtiger/yii2-application/license)](https://packagist.org/packages/yongtiger/yii2-application)


## FEATURES

* `beforeInit()` and `afterInit()` in application
* remote appplication callback, e.g. calling frontend cache flushing from backend


## DEPENDENCES


## INSTALLATION   

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yongtiger/yii2-application "*"
```

or add

```json
"yongtiger/yii2-application": "*"
```

to the require section of your composer.json.


## CONFIGURATION

* \common\config\params.php

```php
'yongtiger.application.remoteAppConfigs' => [
    'app-backend' => [
        'class' => 'backend\\components\\Application',	///optional
        '@common/config/main.php',
        '@common/config/main-local.php',
        '@backend/config/main.php',
        '@backend/config/main-local.php',
    ],
    'app-frontend' => [
        'class' => 'frontend\\components\\Application',	///optional
        '@common/config/main.php',
        '@common/config/main-local.php',
        '@frontend/config/main.php',
        '@frontend/config/main-local.php',
    ],
],
```


## USAGE

* `beforeInit()` and `afterInit()`

```php
class Application extends \yongtiger\application\Application 
{
    /**
     * @inheritdoc
     */
    public function beforeInit() {
        
        parent::beforeInit();

        ///[v0.10.5 (filter theme bootstrap)]
        ///You can still run without yii2 extension `yongtiger/yii-theme`.
        ///Note: Cannot use `class_exists('yongtiger\\theme\\Bootstrap')` before application init!
        if (is_file($this->getVendorPath() . DIRECTORY_SEPARATOR . 'yongtiger'. DIRECTORY_SEPARATOR . 'yii2-theme' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Bootstrap.php')) {

            \yongtiger\theme\Bootstrap::filterExtensionsBootstrap();

        }
        
    }

    /**
     * @inheritdoc
     */
    public function afterInit() {
        
        parent::afterInit();

    }
}
```

* remote appplication callback

```php
Application::remoteAppCall('app-frontend', function($app) {
    $app->cache->flush();
}, function ($config) {
    unset($config['bootstrap']);    ///[yii2-brainbase v0.3.0 (admin:rbac):fix Yii debug disappear in route]
    return $config;
});
```


## NOTES


## DOCUMENTS


## SEE ALSO


## TODO



## [Development roadmap](docs/development-roadmap.md)


## LICENSE 
**Yii2-application** is released under the MIT license, see [LICENSE](https://opensource.org/licenses/MIT) file for details.
