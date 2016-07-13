<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @var string $className the new migration class name
 */

echo "<?php\n";
?>

use dektrium\rbac\migrations\Migration;

class <?= $className ?> extends Migration
{
    public function safeUp()
    {
    
    }
    
    public function safeDown()
    {
        echo "<?= $className ?> cannot be reverted.\n";
        
        return false;
    }
}
