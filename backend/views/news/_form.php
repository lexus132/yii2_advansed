<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin([
    'options'=>['enctype'=>'multipart/form-data']
]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image_file')->widget(FileInput::className(), [
	'options' => ['accept' => 'image/*'],
	'pluginOptions' => [
	    'initialPreview' => !empty($model->image) ? Html::img($model->getImageUrl(), ['class'=>'file-preview-image', 'style' => 'max-width: 213px;max-height: 160px;']) : '',
	    'overwriteInitial' => true,
	    'showRemove' => false,
	    'showUpload' => false
	]
    ]) ?>

     <?= $form->field($model, 'enabled')->widget(Select2::classname(), [
	'data' => $model::getStatuses(),
	'pluginOptions' => [
	    'allowClear' => false
	],
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model,'publish_date')->widget(DatePicker::className(),['dateFormat' => 'yyyy-MM-dd']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>