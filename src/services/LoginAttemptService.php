<?php

namespace jorenvanhee\templateguard\services;

use Craft;
use craft\helpers\DateTimeHelper;
use craft\helpers\Db;
use DateTime;
use jorenvanhee\templateguard\records\LoginAttempt;
use yii\base\Component;

class LoginAttemptService extends Component
{
    const MAX_ATTEMPTS = 5;
    const MAX_ATTEMPTS_PERIOD_IN_SECONDS = 300;

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

        return $count >= self::MAX_ATTEMPTS;
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
            self::MAX_ATTEMPTS_PERIOD_IN_SECONDS
        );

        return DateTimeHelper::currentUTCDateTime()->sub($period);
    }
}
