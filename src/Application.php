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

/**
 * Class Application
 *
 * @package yongtiger\application
 */
class Application extends \yii\web\Application
{
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
}
