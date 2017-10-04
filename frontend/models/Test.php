<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%test}}".
 *
 * @property string $id ID
 * @property string $name Name
 * @property string $slug Name
 * @property int $type Type
 * @property string $description Descriptino
 * @property string $created_at
 * @property string $updated_at
 */
class Test extends \yii\db\ActiveRecord
{
    const SOME_DATA_ONE = 1;

    public function behaviors()
    {
        $item = parent::behaviors();
        $item[] = [
                'class' => SluggableBehavior::className(),
                'slugAttribute' => 'slug',
                'attribute' => 'name',
                'ensureUnique'  => true,
                'immutable'     => true,
            ];
        return $item;
    }

    public function init(){

    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%test}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'description'], 'required'],
            [['type'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 70],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'type' => 'Type',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
