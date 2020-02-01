<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Task;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'author_id',
            'title',
            [
                'attribute' => 'is_parent',
                'label' => 'Это материнский проект?',
                'value' => function (common\models\Project $model) {
                    return $model->is_parent ? "Да" : "Нет";
                },
            ],
            [
                'attribute' => 'parent_project_id',
                'label' => 'Какой у него материнский проект?',
                'format' => 'raw',
                'value' => function (common\models\Project $model) {
                    return Html::a($model->parent_project_id, ['/project/view', 'id' => $model->id]);
                }
            ],
            'description:ntext',
            'priority_id',
            'status',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?php if ($model->tasks) : ?>
        <hr>
        <h2> Tasks </h2>
        <?= GridView::widget([
            'dataProvider' => $taskDataProvider,
            'filterModel' => $taskSearchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                [
                    'attribute' => 'author_id',
                    'value' => function (Task $model) {
                        return $model->author_id;
                    }
                ],
                'status',
            ],
        ]); ?>
    <?php else : ?>
        <p> No tasks! </p>
    <?php endif; ?>




</div>