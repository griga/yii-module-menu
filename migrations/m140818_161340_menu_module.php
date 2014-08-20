<?php

class m140818_161340_menu_module extends DbMigration
{
	public function up()
	{
        $this->createTable('{{menu}}',[
            'id'=>'pk',
            'name'=>'VARCHAR(255)',
            'alias'=>'VARCHAR(255)',
            'enabled'=>'TINYINT NOT NULL DEFAULT 0',
            'type'=>'TINYINT NOT NULL',
            'parent_id'=>'INT NULL DEFAULT 0',
            'image_id'=>'INT',
            'sort'=>'INT NULL DEFAULT NULL',
        ]);
        if (!db()->getSchema()->getTable('{{menu_lang}}')) {
            $this->createTable('{{menu_lang}}', [
                'l_id' => 'pk',
                'entity_id' => 'INT NOT NULL',
                'lang_id' => 'VARCHAR(6) NOT NULL',
                'l_name' => 'VARCHAR(255)',
            ]);
            $this->createIndex('m_ei', '{{menu_lang}}', 'entity_id');
            $this->createIndex('m_li', '{{menu_lang}}', 'lang_id');

            $this->addForeignKey('m_ibfk_1', '{{menu_lang}}', 'entity_id', '{{menu}}', 'id', 'CASCADE', 'CASCADE');

        }
	}



    public function down()
    {
        $this->dropTable('{{content_lang}}');

        $this->dropTable('{{content}}');
    }


}