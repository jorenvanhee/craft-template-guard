<?php

namespace jorenvanhee\templateguard;

use Craft;
use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use jorenvanhee\templateguard\models\Settings;
use jorenvanhee\templateguard\services\GuardService;
use jorenvanhee\templateguard\services\LoginAttemptService;
use jorenvanhee\templateguard\variables\TemplateGuardVariable;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    public $hasCpSettings = true;

    public function init()
    {
        parent::init();

        // Register services
        $this->setComponents([
            'guard' => GuardService::class,
            'loginAttempt' => LoginAttemptService::class,
        ]);

        // Register variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $e) {
                $variable = $e->sender;
                $variable->set('templateGuard', TemplateGuardVariable::class);
            }
        );

        // Register routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $route = $this->getSettings()->loginRoute;
                $event->rules[$route] = 'template-guard/sessions/create';
            }
        );
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'template-guard/settings',
            [ 'settings' => $this->getSettings() ]
        );
    }
}
