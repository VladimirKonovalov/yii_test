<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Main';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'file',
                'label' => 'Изображение',
                'value' => 'file.path',
                'format' => 'image',
            ],
            [
                'attribute' => 'username',
                'label' => 'Пользователь',
                'value' => 'username',
            ],
            [
                'attribute' => 'organizationName',
                'label' => 'Организация',
                'value' => 'organizationName',
            ],


  //          ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>