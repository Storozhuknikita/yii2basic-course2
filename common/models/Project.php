<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * Class Project
 * @package common\models
 * @property int $id [int(11)]
 * @property int $author_id [int(11)]
 * @property string $title [varchar(255)]
 * @property string $description
 * @property bool $priority [tinyint(3)]
 * @property bool $status [tinyint(3)]
 * @property int $created_at [bigint(20)]
 * @property ActiveQuery $tasks
 * @property int $updated_at [bigint(20)]
 */
class Project extends \yii\db\ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'project';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['author_id', 'priority', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'title' => 'Title',
            'description' => 'Description',
            'priority' => 'Priority',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['project_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPriority()
    {
        return $this->hasOne(Priority::class, ['id' => 'priority_id', 'type' => Priority::TYPE_PROJECT]);
    }
}