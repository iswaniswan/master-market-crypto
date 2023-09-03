<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class UplonAlert extends Widget
{
    const DANGER = 'alert-danger';
    const SUCCESS = 'alert-success';
    const WARNING = 'alert-warning';
    const INFO = 'alert-info';


    public $type;
    public $title;
    public $message;
    public $actionUrl;


    public function run()
    {
        $html = <<<HTML
            <div class="{$this->type}" role="alert" style="padding:15px;margin-bottom:20px;border:1px solid transparent;border-radius:4px">
                <strong style="text-transform:uppercase">$this->title</strong>
                <a href="{$this->actionUrl}" class="alert-link">$this->message</a>
            </div>
        HTML;

        return $html;
    }
}
