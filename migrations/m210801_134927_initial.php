<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210801_134927_initial
 */
class m210801_134927_initial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // create test list of organizations
        $this->createTable('organization', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
        $this->insert('organization', [
            'name' => 'Intel',
        ]);
        $this->insert('organization', [
            'name' => 'AMD',
        ]);
        $this->insert('organization', [
            'name' => 'ARM',
        ]);

        // create table user and foreign key to organization
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'organization_id' => $this->integer()->notNull(),
            'position' => $this->string()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-user-organization_id',
            'user',
            'organization_id',
            'organization',
            'id',
            'CASCADE'
        );

        // create table file, add foreign key to user
        // type = 1; picture
        // type = 2; document
        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'path' => $this->string()->notNull(),
            'type' => $this->integer()->notNull()->comment("type=1 picture, type=2 document"),
            'user_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-file-user_id',
            'file',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210801_134927_initial cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210801_134927_initial cannot be reverted.\n";

        return false;
    }
    */
}
