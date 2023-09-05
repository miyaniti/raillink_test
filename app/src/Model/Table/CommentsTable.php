<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;
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
class commentsTable extends AppTable
{
    const TABLE = "comments";

    const PUBLIC_FLAG = 2;
    const FORBIDDEN_WORDS = [
        "aaa",
        "abc"
        //削除対象のコメントを追加していく
    ];
    /**
    * Initialize method
    *
    * @param array $config The configuration for the Table.
    * @return void
    */
    public function initialize(array $config): void
    {
        $this->setTable('comments');
        $this->setDisplayField('comment');
        $this->setPrimaryKey('id');

        parent::initialize($config);
    }

    public function getComment($id)
    {
        //dd($this->find("all")->toArray());
        return $this->find()
            ->where(['thread_id' => $id])
            ->where(['flag' => self::PUBLIC_FLAG]);
    }

    public function getData($id)
    {
        return $this->find()
            ->where(['id' => $id])
            ->where(['flag' => self::PUBLIC_FLAG])
            ->first();
    }
    /*
    public function deleteComment($id)
    {
        $updateComment = $this->get($id);
        $updateComment->flag = 1;
        $this->save($updateComment);
    }
    */
    public function deleteComment($comment_id)
    {
        try {
            $deleteComment = $this->get($comment_id);
            $this->delete($deleteComment);
        } catch (RecordNotFoundException $e) {
            return false;
        }
    }

    public function deleteComments($thread_id)
    {
        $this->deleteAll(['thread_id IN' => $thread_id]);
        /*
        $deleteComment = $this->find('all')
            ->where(['thread_id' => $thread_id])
            ->toList();
        $this->deleteAll($deleteComment);
        */
    }

    public function createComment($comment){
        //dd($comment["user_name"]);
        $newComment = $this->newEntity();
        $newComment->thread_id = $comment["thread_id"];
        //ユーザー名入力されているか確認
        if(!empty($comment["user_name"])){
            $newComment->user_name = $comment["user_name"];
        }
        else{
            $newComment->user_name = "匿名";
        }
        if(!empty($comment["user_id"])){
            $newComment->user_id = $comment["user_id"];
        }
        $newComment->comment = $comment["comment"];
        //$newComment->user_name = $this->newEntity($comment["user_name"]);
        //$newComment->comment = $this->newEntity($comment["comment"]);
        $this->save($newComment);
    }

    //バッチ処理
    public function removeComment(){
        //$commentsTable = $this->find("all")->toArray();
        $conditions = [
            'comment IN' => self::FORBIDDEN_WORDS // FORBIDDEN_WORDSは禁止用語の配列です
        ];
        $query = $this->find()->where($conditions);
        //dd($query);
        // 3. 検索結果を取得し、それらのレコードを削除する
        foreach ($query as $comment) {
            $this->delete($comment);
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
