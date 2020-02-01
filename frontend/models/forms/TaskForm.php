<?php
namespace frontend\models\forms;
use common\models\Tasks;
use frontend\common\behaviors\ChatNotificationBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
class TaskForm extends Tasks
{
    const STATUS_NEW = 'New';
    public function behaviors()
    {
        /** поведение для установки даты создания, и даты редактирование текущей датой */
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
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'status',
                ],
                'value' => self::STATUS_NEW
            ],
            [
                'class' => ChatNotificationBehavior::class,
            ],
        ];
    }
    public function rules()
    {
        $rules = [
            ['project_id', 'required',
                'when' => function( $model ) {
                    return !(boolean)$model->is_template;
                    /** исправил написание, YII намешал своего присылая данные формы, нужно чистить */
                }, 'whenClient' => '() => document.querySelector( \'#taskform-project_id\').value.trim()'
            ]
        ];
        return ArrayHelper::merge( parent::rules(), $rules );
    }
}