<?php

namespace jorenvanhee\templateguard\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use craft\web\View;
use jorenvanhee\templateguard\Plugin;
use yii\web\BadRequestHttpException;

class SessionsController extends Controller
{
    protected array|bool|int $allowAnonymous = self::ALLOW_ANONYMOUS_LIVE;

    public function actionCreate(): Response
    {
        $ref = Craft::$app->request->getParam('ref');
        if ($ref) {
            $value = Craft::$app->security->validateData($ref);

            if ($value === false) {
                throw new BadRequestHttpException('Request contained an invalid query param');
            }
        }

        $settings = Plugin::getInstance()->getSettings();

        Craft::$app->response->headers->set('X-Robots-Tag', 'noindex');

        if ($settings->template) {
            return $this->renderTemplate($settings->template, ['ref' => $ref ? $value : null], View::TEMPLATE_MODE_SITE);
        }

        return $this->renderTemplate('template-guard/login', ['ref' => $ref ? $value : null], View::TEMPLATE_MODE_CP);
    }

    public function actionDelete(): Response
    {
        $this->requirePostRequest();

        $key = $this->request->getBodyParam('key');

        Plugin::getInstance()->guard->logout($key);

        return $this->redirect($this->request->referrer);
    }
}
