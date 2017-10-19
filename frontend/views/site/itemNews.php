<?php if(!empty($model)){ ?>
    <?php foreach($model as $item){ ?>
	<div class="col-sm-4 col-xs-6" style=" height: 430px;">

	    <a href="<?= (!empty($item['link']))? $item['link'] : ''; ?>"><img src="<?= (!empty($item['image']))? $item->getImageUrl() : ''; ?>" style="max-width: 100%; max-height: 50%;"></a>

	    <a href="<?= (!empty($item['link']))? $item['link'] : ''; ?>"><h3><?= (!empty($item['title']))? \yii\helpers\StringHelper::truncate($item['title'],45,'...') : ''; ?></h3></a>

	    <p><?= (!empty($item['description']))? $item['description'] : ''; ?></p>

	    <p><a class="btn btn-default" href="<?= (!empty($item['link']))? $item['link'] : ''; ?>">Reed more &raquo;</a></p>
	</div>
    <?php } ?>
<?php } ?>