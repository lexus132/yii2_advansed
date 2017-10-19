
<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'test';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-test">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(!empty($data)){ ?>
        <blockquote>
            <?php
            echo '<pre>';
            print_r($data);
            echo '</pre>';
            ?>
        </blockquote>
    <?php } ?>
</div>
