<?php

use yii\db\Migration;

/**
 * Class m200203_185156_create_table_task_subscriber
 */
class m200203_185156_create_table_task_subscriber extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task_subscriber', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'task_id' => $this->integer(),
            'type' => $this->integer(),
            'created_at' => $this->bigInteger(),
            'updated_at' => $this->bigInteger()

        ]);

        $this->addForeignKey(
            'fk-task_subscriber-user_id',
            'task_subscriber',
            'user_id',
            'user',
            'id'
        );

        $this->addForeignKey(
            'fk-task_subscriber-task_id',
            'task_subscriber',
            'task_id',
            'task',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task_subscriber-user_id', 'task_subscriber');
        $this->dropForeignKey('fk-task_subscriber-task_id', 'task_subscriber');
        $this->dropTable('task_subscriber');
    }

}
