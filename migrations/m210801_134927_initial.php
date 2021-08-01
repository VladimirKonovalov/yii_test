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
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // create test list of organizations
        $this->createTable('organization', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);
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
            'auth_key' => $this->string(32)->notNull(),
        ], $tableOptions);
        $this->addForeignKey(
            'fk-user-organization_id',
            'user',
            'organization_id',
            'organization',
            'id',
            'CASCADE'
        );

        // create table file, add foreign key to user
        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'path' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey(
            'fk-file-user_id',
            'file',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // create table document, add foreign key to user
        $this->createTable('document', [
            'id' => $this->primaryKey(),
            'path' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey(
            'fk-document-user_id',
            'document',
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
