<?php

namespace frontend\common\behaviors;

use common\models\Projects;
use common\models\Tasks;
use common\models\User;
use frontend\models\ChatLog;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class ChatNotificationBehavior extends Behavior
{
    const MODE_INSERT = 0;
    const MODE_UPDATE = 1;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'sendInsertNotification',
            ActiveRecord::EVENT_AFTER_UPDATE => 'sendUpdateNotification'
        ];
    }

    public function sendInsertNotification() {
        $this->sendNotification(self::MODE_UPDATE);
    }

    public function sendUpdateNotification() {
        $this->sendNotification(self::MODE_UPDATE);
    }

    private function sendNotification( $mode = self::MODE_INSERT ) {
        $model = $this->owner;
        /** @var $model Tasks|Projects */
        $creator = User::findOne([ 'id' => $model->create_user_id ?? null ]);

        $currentDateTime = date('d-m-Y H:i');
        $chatLogBody = [];
        if ( $model instanceof Projects ) {
            $message = $mode === self::MODE_INSERT ? 'создан' : 'обновлен';
            $chatLogBody = [
                "project_id" => $model->id,
                "message" => "Проект {$message} {$currentDateTime}",
                "username" => User::SYSTEM,
                "user_id" => $creator ? $creator->id : null
            ];
        } elseif ( $model instanceof Tasks ) {
            $message = $mode === self::MODE_INSERT ? 'создана' : 'обновлена';
            $chatLogBody = [
                "task_id" => $model->id,
                "project_id" => $model->project_id,
                "message" => "Задача {$message} {$currentDateTime}",
                "username" => User::SYSTEM,
                "user_id" => $creator ? $creator->id : null
            ];
        }
        ChatLog::create($chatLogBody);
    }
}