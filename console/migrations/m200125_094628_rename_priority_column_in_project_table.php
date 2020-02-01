<?php

use yii\db\Migration;

/**
 * Class m200125_094628_rename_priority_column_in_project_table
 */
class m200125_094628_rename_priority_column_in_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('project','priority',  'priority_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('priority','priority_id','priority');
    }


}
