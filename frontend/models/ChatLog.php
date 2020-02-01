<?php

namespace frontend\models;

use common\models\Project;
use common\models\Task;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Class ChatLog
 * @package frontend\models
 * @property int $id [int(11)]
 * @property string $username [varchar(255)]
 * @property string $message
 * @property int $created_at [bigint(20)]
 * @property int $updated_at [bigint(20)]
 * @property int $task_id [bigint(20)]
 * @property int $project_id [bigint(20)]
 * @property bool $type [tinyint(3)]
 *
 * @property Task $task
 * @property Project $project
 */
class ChatLog extends \yii\db\ActiveRecord
{
    const SHOW_HISTORY = 1;
    const SEND_MESSAGE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['created_at', 'updated_at', 'type', 'task_id', 'project_id'], 'integer'],
            [['username', 'type'], 'required'],
            [['message'], 'string'],
            [['username'], 'string', 'max' => 255],
        ];

        if ($this->type == self::SEND_MESSAGE) {
            $rules[] = [['message'], 'required'];
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class => ['class' => TimestampBehavior::class],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'message' => 'Message',
            'task_id' => 'Task Id',
            'project_id' => 'Project Id'
        ];
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function create(array $data)
    {
        try {
            $model = new self([
                'username' => $data['username'],
                'message' => $data['message'],
                'type' => $data['type'],
                'task_id' => $data['task_id'] ?? null,
                'project_id' => $data['project_id'] ?? null
            ]);

            if ($model->save()) {
                return true;
            } else {
                var_dump($model->errors);
            };
        } catch (\Throwable $throwable) {
            Yii::error($throwable->getTraceAsString());
            Yii::error(json_encode($data));
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getTask(){
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProject(){
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }
}