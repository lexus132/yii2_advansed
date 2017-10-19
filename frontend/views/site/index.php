<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

$this->registerJsFile('/js/smallLoad.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs("
    $(document).ready(function(){
	$('#load_more').on('click', function(){
	    $.ajax({
		url:'".yii\helpers\Url::to(['/site/load-more'])."?next='+$('#load_more').val(),
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
		    if(response.successful){
			    $('#content_body').append(response.successful);
			    $('#load_more').val($('#load_more').val()*1 + 1);
		    } else {
			$('button#load_more').hide();
		    }
		},
		complete: function(response) {
		    $.loadingBlockHide();
		}
	    });
	});
		
	$('#search').on('click', function(){
	    var data = $('input[name=\"search\"]');
	    var form = $('form#search_form');
	    if((data.val()).length > 0){
		console.log(form.serialize());
		$.ajax({
		    url:'".yii\helpers\Url::to(['/site/search'])."',
		    type: 'POST',
		    data: form.serialize(),
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
			if(response.successful){
				$('#content_body').html(response.successful);
				$('#load_more').hide();
			} else {
//			    $('#load_more').hide();
			}
		    },
		    complete: function(response) {
			$.loadingBlockHide();
		    }
		});
	    }
	});
    });
");
?>
<div class="site-index">

	<div class="row" style="margin-bottom: 30px;">
	    <div class="col-sm-6 col-sm-offset-3">
		<form method="POST" id="search_form">
		    <div class="input-group">
		      <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
		      <input name="search" type="text" class="form-control" placeholder="Search for..." value="">
		      <span class="input-group-btn">
			  <button id="search" class="btn btn-secondary" type="button"><span class="glyphicon glyphicon-search"></span></button>
		      </span>
		    </div>
		</form>
	    </div>
	</div>

    <div class="body-content">

        <div id="content_body" class="row">
	    
	    <?php if(!empty($model)){ ?>
		<?php foreach($model as $item){ ?>
		    <div class="col-sm-4 col-xs-6" style=" height: 430px;">

			<a href="<?= (!empty($item->link))? $item->link : ''; ?>"><img src="<?= (!empty($item->image))? $item->getImageUrl() : ''; ?>" style="max-width: 100%; max-height: 50%;"></a>

			<a href="<?= (!empty($item->link))? $item->link : ''; ?>"><h3><?= (!empty($item->title))? \yii\helpers\StringHelper::truncate($item->title,45,'...') : ''; ?></h3></a>

			<p><?= (!empty($item->description))? \yii\helpers\StringHelper::truncate($item->description,45,'...') : ''; ?></p>

			<p><a class="btn btn-default" href="<?= (!empty($item->link))? $item->link : ''; ?>">Reed more &raquo;</a></p>
		    </div>
		<?php } ?>
	    <?php } ?>
        </div>
	
	<div class="row">
	    <button id="load_more" type="button" class="btn btn-lg btn-success" value="<?= 1 ?>">Load more</button>
	</div>
	
    </div>
</div>
