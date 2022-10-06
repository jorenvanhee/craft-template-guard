<?php

namespace jorenvanhee\templateguard\services;

use Craft;
use craft\helpers\DateTimeHelper;
use craft\helpers\Db;
use DateTime;
use jorenvanhee\templateguard\records\LoginAttempt;
use jorenvanhee\templateguard\Plugin;
use yii\base\Component;

class LoginAttemptService extends Component
{
    public function register(string $key)
    {
        $attempt = new LoginAttempt();
        $attempt->key = $key;
        $attempt->ipAddress = Craft::$app->request->userIP;
        $attempt->save();

        if (rand(1, 10) === 1) {
            $this->_cleanDatabase();
        }
    }

    public function tooMany(string $key): bool
    {
        $start = $this->_maxAttemptsPeriodStart();

        $count = LoginAttempt::find()
            ->where([
                'key' => $key,
                'ipAddress' => Craft::$app->request->userIP,
            ])
            ->andWhere(['>=', 'dateCreated', Db::prepareDateForDb($start)])
            ->count();

        return $count >= Plugin::getInstance()->getSettings()->maxLoginAttempts;
    }

    private function _cleanDatabase()
    {
        $start = $this->_maxAttemptsPeriodStart();

        LoginAttempt::deleteAll(
            ['<', 'dateCreated', Db::prepareDateForDb($start)]
        );
    }

    private function _maxAttemptsPeriodStart(): DateTime
    {
        $period = DateTimeHelper::secondsToInterval(
          Plugin::getInstance()->getSettings()->maxLoginAttemptsPeriodInSeconds
        );

        return DateTimeHelper::currentUTCDateTime()->sub($period);
    }
}
