<?php

namespace frontend\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

class News extends \common\models\News
{
    
    
    public function getImageUrl(){
	return '/image/'.$this->image;
    }
}
