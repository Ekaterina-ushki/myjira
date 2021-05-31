<?php

use yii\db\Migration;

/**
 * Class m290521003725_add_types__statuses_service_class
 */
class m290521_003725_add_types_statuses_service_class extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('type', array('name'=>'research'));
        $this->insert('type', array('name'=>'development'));
        $this->insert('type', array('name'=>'bug'));
        $this->insert('type', array('name'=>'sales'));

        $this->insert('status', array('name'=>"to be"));
        $this->insert('status', array('name'=>"in progress"));
        $this->insert('status', array('name'=>"testing"));
        $this->insert('status', array('name'=>"acceptance"));
        $this->insert('status', array('name'=>"done"));

        $this->insert('service_class', array('name'=>"expedite"));
        $this->insert('service_class', array('name'=>"fixed date"));
        $this->insert('service_class', array('name'=>"standard"));
        $this->insert('service_class', array('name'=>"accelerating"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m290521003725_add_types__statusesServiceclass cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m290521003725_add_types__statusesServiceclass cannot be reverted.\n";

        return false;
    }
    */
}
