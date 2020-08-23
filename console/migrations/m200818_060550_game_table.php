<?php

use yii\db\Migration;

/**
 * Class m200818_060550_game_table
 */
class m200818_060550_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%game}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'icon' => $this->string(),
            'genre_id' => $this->integer()->notNull()->unsigned(),
            'publisher_id' => $this->integer()->notNull()->unsigned(),
            'rate' => $this->decimal(2,1)->notNull(),
            'installs' => $this->integer()->unsigned(),
            'url' => $this->string()
        ], $tableOptions);

        $this->createIndex('idx_game_genre_id','{{%game}}','genre_id');
        $this->addForeignKey(
            'fk-game-genre_id',
            '{{%game}}',
            'genre_id',
            '{{%genre}}',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx_game_publisher_id','{{%game}}','publisher_id');
        $this->addForeignKey(
            'fk-game-publisher_id',
            '{{%game}}',
            'publisher_id',
            '{{%publisher}}',
            'id',
            'CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-game-genre_id','{{%game}}');
        $this->dropIndex('idx_game_genre_id','{{%game}}');
        $this->dropForeignKey('fk-game-publisher_id','{{%game}}');
        $this->dropIndex('idx_game_publisher_id','{{%game}}');

        $this->dropTable('{{%game}}');
    }

}
