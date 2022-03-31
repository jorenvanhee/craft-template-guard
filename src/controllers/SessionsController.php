<?php

namespace jorenvanhee\templateguard\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use craft\web\View;
use jorenvanhee\templateguard\Plugin;

class SessionsController extends Controller
{
    protected array|bool|int $allowAnonymous = self::ALLOW_ANONYMOUS_LIVE;

    public function actionCreate(): Response
    {
        $settings = Plugin::getInstance()->getSettings();

        Craft::$app->response->headers->set('X-Robots-Tag', 'noindex');

        if ($settings->template) {
            return $this->renderTemplate($settings->template, [], View::TEMPLATE_MODE_SITE);
        }

        return $this->renderTemplate('template-guard/login', [], View::TEMPLATE_MODE_CP);
    }
}
