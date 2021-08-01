<?php

use app\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Main';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin() ?>

    <button class="btn btn-primary">Export xlsx</button>

    <?php ActiveForm::end() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'filepath',
                'label' => 'Изображение',
                'value' => 'filepath',
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
//            [
//                'attribute' => 'documentList',
//                'label' => 'Файлы',
//                'value' => 'documentList',
//            ],


  //          ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>