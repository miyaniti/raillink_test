<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Threds Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Admins
 *
 * @method \App\Model\Entity\News get($primaryKey, $options = [])
 * @method \App\Model\Entity\News newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\News[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\News|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\News patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\News[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\News findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends AppTable
{
    const TABLE = "users";

    const PUBLIC_FLAG = 2;
    /**
    * Initialize method
    *
    * @param array $config The configuration for the Table.
    * @return void
    */
    public function initialize(array $config): void
    {
        $this->setTable('users');
        $this->setDisplayField('user_name');
        $this->setPrimaryKey('id');

        parent::initialize($config);
    }

    public function findByUsername($user_name)
    {
        return $this->find()
            ->where(['user_name' => $user_name]);
    }

    public function getUser($username)
    {
        return $this->find()
            ->select(['id'])
            ->where(['user_name' => $username])
            ->first();
    }
    public function getlist($username)
    {
        return $this->find()
            ->where(['user_name' => $username])
            ->first();
    }

    /**
    * Default validation rules.
    *
    * @param \Cake\Validation\Validator $validator Validator instance.
    * @return \Cake\Validation\Validator
    */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');
        return $validator;
    }

    

    /**
    * Returns a rules checker object that will be used for validating
    * application integrity.
    *
    * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
    * @return \Cake\ORM\RulesChecker
    */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        return $rules;
    }
}
