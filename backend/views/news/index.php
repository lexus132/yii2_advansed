<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/smallLoad.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs("
    $(document).ready(function(){
	$('#parse_news').on('click', function(){
	    $('#help_blok').html('');
	    $.ajax({
		url:'". \Yii::$app->urlManager->createUrl(['news/parser']) ."',
		type: 'POST',
		contentType: 'json',
		beforeSend: function(){
		    $.loadingBlockShow({
			imgPath: '/img/default.svg',
			text: 'News Loading ...',
			style: {
			    position: 'fixed',
			    width: '100%',
			    height: '100%',
			    background: 'rgba(0, 0, 0, .8)',
			    color: '#fff',
			    left: 0,
			    top: 0,
			    zIndex: 10000
			}
		    });
		},
		success: function (response) {
		    console.log(response);
		    if(response.errors){
			for(var iteEr in response.errors){
			    $('#help_blok').append('<div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\" title=\"close\">×</a><strong>Warning!</strong> ' + response.errors[iteEr] + '</div>');
			}
		    } else if(response.successful){
			window.location.reload();
		    }
		},
		complete: function(response) {
		    $.loadingBlockHide();
//		    console.log(response);
		}
	    });
//	    return false;
	});
    });
");
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <div id="help_blok"></div>
    <p>
        <?= Html::button('Get news', ['class' => 'btn btn-success', 'id' => 'parse_news']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

	    [
		'attribute' => 'title',
		'options' => ['width' => '200']
	    ],
	    [
		'attribute' => 'slug',
		'options' => ['width' => '200']
	    ],
	    [
		'attribute' => 'description',
		'format' => 'text',    
		'options' => ['width' => '300']
	    ],
	    [
		'attribute' => 'image',
		'format' => 'html',    
		'value' => function ($data) {
		    return Html::img($data->getImageUrl(),
			['width' => '70px']);
		},
	    ],
	    [
		'attribute' => 'enabled',
		'value' => function ($data) {
		    return $data->getStatus();
		},
	    ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>