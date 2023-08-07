<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * Class AppTable
 * @package App\Model\Table
 */
class AppTable extends Table
{
    const TABLE = null;

    protected $conn = null;


    /**
    * Initialize method
    *
    * @param array $config The configuration for the Table.
    * @return void
    */
    public function initialize(array $config)
    {
        parent::initialize($config);

        if (static::TABLE) {
            $this->setTable(static::TABLE);
        }

        $this->setAlias($this->_table);
        $this->conn = ConnectionManager::get('default');
        $this->addBehavior('Timestamp');
    }
}
