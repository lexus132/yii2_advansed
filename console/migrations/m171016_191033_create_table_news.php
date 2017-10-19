<?php

use platx\db\Migration;

/**
 * Class m171016_191033_create_table_news
 */
class m171016_191033_create_table_news extends Migration
{
     /**
    * @var string Table name for migrate
    */
    protected $_tableName='{{%news}}';
    /**
    * @inheritdoc
    */
    public function safeUp()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'title' => $this->string()->comment('Tile'),
            'link' => $this->string()->comment('link'),
            'slug' => $this->string()->comment('Slug'),
	    'image' => $this->string(),
	    'enabled' => $this->smallInteger()->defaultValue(1)->notNull(),
            'description' => $this->text()->comment('Descriptino'),
            'publish_date' => $this->datetime(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime()->defaultValue(null),
        ], $this->_tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable($this->_tableName);
    }
}
