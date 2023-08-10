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
class ThreadsTable extends AppTable
{
    const TABLE = "threads";

    const PUBLIC_FLAG = 2;
    /**
    * Initialize method
    *
    * @param array $config The configuration for the Table.
    * @return void
    */
    public function initialize(array $config): void
    {
        $this->setTable('threds');
        $this->setDisplayField('tread');
        $this->setPrimaryKey('id');

        parent::initialize($config);
    }

    public function getList()
    {
        return $this->find()
            ->where(['flag' => self::PUBLIC_FLAG]);
    }

    public function getThreds($id)
    {
        return $this->find()
            ->where(['flag' => self::PUBLIC_FLAG])
            ->where(['id' => $id]);
    }

    public function getData($id)
    {
        return $this->find()
            ->where(['id' => $id])
            ->where(['flag' => self::PUBLIC_FLAG])
            ->first();
    }

    public function createThreds($thread){
        $newThread = $this->newEntity($thread);
        $this->save($newThread);
    }
    /*
    public function deleteThread($id){
        $updateThread = $this->get($id);
        $updateThread->flag = 1;
        $this->save($updateThread);
    }
    */
    public function deleteThread($id){
        if ($this->get($id)) {
            $deleteThread = $this->get($id);
            $this->delete($deleteThread);
        }
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
