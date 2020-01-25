<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
 * @property int $updated_at [bigint(20)]
 */
class Project extends \yii\db\ActiveRecord
{
    private $is_parent;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [TimestampBehavior::class => ['class' => TimestampBehavior::class]];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'priority_id', 'status', 'created_at', 'updated_at', 'parent_project_id'], 'integer'],
            [['description'], 'string'],
            [['is_parent'], 'boolean'],
            [['title'], 'string', 'max' => 255],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'title' => 'Title',
            'description' => 'Description',
            'priority_id' => 'Priority',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['project_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(Project::class, ['parent_project_id' => 'id']);
    }

    public function getPriority()
    {
        return $this->hasOne(Priority::class, ['id' => 'priority_id', 'type' => Priority::TYPE_PROJECT]);
    }
}