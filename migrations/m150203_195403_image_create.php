<?php

use yii\db\Schema;
use yii\db\Migration;

class m150203_195403_image_create extends Migration
{
    public function up()
    {
        $this->createTable(
            'image',
            [
                'id' => Schema::TYPE_PK,
                'fileId' => Schema::TYPE_INTEGER . " NOT NULL",
                'width' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
                'height' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
                'provider' => Schema::TYPE_STRING . ' NOT NULL',
            ]
        );
    }

    public function down()
    {
        $this->dropTable('image');
    }
}
