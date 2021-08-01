<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\models\Organization;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\widgets\Alert::widget() ?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to signup:</p>
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'organization_id')->dropDownList(Organization::getList(),['prompt' => 'Выберите организацию...']) ?>
            <?= $form->field($model, 'position')->textInput() ?>
            <?= $form->field($model, 'imageFile')->fileInput() ?>

            <?= $form->field($model, 'documentFiles[]')->fileInput(['multiple' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>