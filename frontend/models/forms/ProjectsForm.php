<?php

namespace frontend\models\forms;

use common\models\Projects;
use frontend\common\behaviors\ChatNotificationBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ProjectsForm extends Projects
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'create_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'update_at',
                ],
                'value' => time()
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'create_user_id',
                ],
                'value' => \Yii::$app->user->id
            ],
            [
                'class' => ChatNotificationBehavior::class,
            ],
        ];
    }

}