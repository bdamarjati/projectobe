<?php

use yii\db\Migration;

/**
 * Class m200107_125931_modifikasi_user
 */
class m200107_125931_modifikasi_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'nama', $this->string()->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'nama');
    }
}
