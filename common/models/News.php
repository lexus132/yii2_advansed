<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property int $id ID
 * @property string $title Tile
 * @property string $slug Slug
 * @property string $image
 * @property int $enabled
 * @property string $description Descriptino
 * @property string $publish_date
 * @property string $created_at
 * @property string $updated_at
 */
class News extends \yii\db\ActiveRecord
{
    
    public function behaviors()
    {
	$items = parent::behaviors();

	$items['slug'] = [
		'class'         => \yii\behaviors\SluggableBehavior::class,
		'slugAttribute' => 'slug',
		'attribute'     => 'title',
		'ensureUnique'  => true,
		'immutable'     => true,
	];
	
	
	$items['date'] = [
		    'class' => TimestampBehavior::className(),
		    'createdAtAttribute' => 'created_at',
		    'updatedAtAttribute' => $this->hasProperty('updated_at') ? 'updated_at' : 'created_at',
		    'value' => function () {
			return date('Y-m-d');
		    },
		];

	return $items;
    }
    
    /**
    * @var mixed image the attribute for rendering the file input
    * widget for upload on the form
    */
    public $image_file;

    
    /**
    * Константы статусов
    */
   const STATUS_DISABLED = 0;
   const STATUS_ENABLED  = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['title', 'image', 'description', 'publish_date'], 'required'],
            [['enabled'], 'integer'],
            [['description'], 'string'],
            [['publish_date'],'date', 'format'=>'yyyy-mm-dd'],
            [['image', 'title', 'slug', 'link'], 'string', 'max' => 255],
            [['image_file'], 'safe'],
            [['image_file'], 'file', 'extensions'=>'jpg, gif, png'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => 'ID',
            'title' => 'Название',
            'link' => 'Ссылка',
            'slug' => 'Slug',
            'image' => 'Изображение',
            'enabled' => 'Статус',
            'description' => 'Описание',
            'publish_date' => 'Дата публикации',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
       ]);
    }
    
    /**
    * Список статусов
    *
    * @param null $key
    * @param null $defaultValue
    *
    * @return array|mixed
    */
    public static function getStatuses($key = null, $defaultValue = null)
    {
	    $items = [
		    self::STATUS_ENABLED  => 'Активен',
		    self::STATUS_DISABLED => 'Отключен',
	    ];

	    return !is_null($key) ? ArrayHelper::getValue($items, $key, $defaultValue) : $items;
    }
   
    /**
    * Название статуса
    *
    * @param null $defaultValue
    *
    * @return array|mixed
    */
    public function getStatus($defaultValue = null)
    {
	    return static::getStatuses($this->enabled, $defaultValue);
    }

    /**
     * @inheritdoc
     * @return NewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsQuery(get_called_class());
    }
    
    public function getImageUrl(){
	return \Yii::$app->urlManagerFrontend->baseUrl.'/image/'.$this->image;
    }
}
