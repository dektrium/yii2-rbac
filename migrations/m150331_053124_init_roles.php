<?php

use yii\db\Schema;
use yii\db\Migration;
use \dektrium\rbac\models\Role;

class m150331_053124_init_roles extends Migration
{
    public function up()
    {

        $role = new Role(['scenario' => 'create']);
        $role->name = 'Admin';
        $role->description = 'Administrator';
        $role->save();

        $role = new Role(['scenario' => 'create']);
        $role->name = 'UserAdmin';
        $role->description = 'User Administrator';
        $role->save();

        $role = new Role(['scenario' => 'create']);
        $role->name = 'UserView';
        $role->description = 'User View';
        $role->save();
        
        return true;
        
    }

    public function down()
    {

        return false;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
