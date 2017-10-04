<?php

use platx\db\Migration;

/**
* Class m171004_214051_create_table_test
*/
class m171004_214051_create_table_test extends Migration
{
    /**
    * @var string Table name for migrate
    */
    protected $_tableName='{{%test}}';
    /**
    * @inheritdoc
    */
    public function safeUp()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'name' => $this->string(70)->notNull()->comment('Name'),
            'slug' => $this->string(70)->notNull()->comment('Slug'),
            'type' => $this->boolean()->defaultValue(1)->comment('Type'),
            'description' => $this->text()->notNull()->comment('Descriptino'),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime()->defaultValue(null),
            //            'KEY (category_id)',
            //            'FOREIGN KEY (category_id) REFERENCES {{%category}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
            //            'KEY (color_id)',
            //            'FOREIGN KEY (color_id) REFERENCES {{%color}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $this->_tableOptions);

    /*
    // creates index for column `author_id`
    $this->createIndex(
    'idx-post-author_id',
    $this->_tableName,
    'author_id'
    );

    // add foreign key for table `user`
    $this->addForeignKey(
    'fk-post-author_id',
    $this->_tableName,
    'author_id',
    '{{%user}}',
    'id',
    'CASCADE'
    );
    */

    $count = 10;
    for($i = 0; $i < $count; $i ++ ){
        $this->insert($this->_tableName, [
            'id' => null,
            'name' => 'user name '.$i,
            'slug' => 'slug_'.$i,
            'type' => $count%2,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'created_at' => date('Y-m-d H-i-s',(time()-$i*3600*24)),
            'updated_at' => null,
        ]);
    }
    /*
    */

//        $this->addColumn($this->_tableName, 'accessories', $this->text()->defaultValue('')->comment('Аксессуары'));
}

/**
* @inheritdoc
*/
    public function safeDown()
    {
        $this->dropTable($this->_tableName);

    //      $this->dropColumn($this->_tableName, 'accessories');

    //      $this->dropForeignKey(
    //          'fk-post-author_id',
    //          $this->_tableName
    //      );
    }

}
