<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Priority
 * @package common\models
 * @property int $id [int(11)]
 * @property string $title [varchar(255)]
 * @property string $order [varchar(255)]
 * @property bool $type [tinyint(3)]
 */
class Priority extends \yii\db\ActiveRecord
{
    const TYPE_PROJECT = 1;
    const TYPE_TASK = 2;


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'priority';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['title', 'order'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'order' => 'Order',
            'type' => 'Type',
        ];
    }


    /**
     * @return array
     */
    public static function getTaskPriorities()
    {
        return ArrayHelper::map(
            self::find()->where(['type' => self::TYPE_TASK])
                ->asArray()
                ->orderBy('order')
                ->all(),
            'id',
            'title');
    }
}