<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Controller\Controller;
use App\Controller\UsersController;
use Cake\Datasource\ConnectionManager;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CommentController extends AppController
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *  
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public $users;
    public function initialize()
    {
        parent::initialize();

        $this->ThreadsTable = TableRegistry::getTableLocator()->get("threads");
        $this->CommentTable = TableRegistry::getTableLocator()->get("comments");
        $this->UsersTable = TableRegistry::getTableLocator()->get("users");
        $this->CommentsgoodTable = TableRegistry::getTableLocator()->get("commentsgood");//アンダースコア(_)いらない
        
    }

    public function comment($id)
    {
        $this->set('threads',$this->ThreadsTable->getThreds($id));
        $this->set('comments',$this->CommentTable->getComment($id));
        $this->set('thread_id', $id);
        //user取得
        if ($this->Auth->user()) {
            
            //$this->set('loggedInUser', $this->Auth->user());//user_name をset
            $userObject = json_decode(json_encode($this->Auth->user()));
            $this->users = $this->UsersTable->getlist($userObject->user_name);
            //dd($this->UsersTable->getlist($userObject->user_name));
        }
        $this->set('users', $this->users);
    }

    public function thread($id)
    {
        //dd($id);
        //dd($this->ThreadsTable->getData($id));
        $this->set($id);


    }

    public function deleteComment($id, $thread_id)
    {
        // 処理結果をビューに渡す
        $this->CommentTable->deleteComment($id);
        $this->CommentsgoodTable->deleteCommentgood($id);
        return $this->redirect("/comments/$thread_id");
    }

    public function deleteComments($thread_id)
    {
        // 処理結果をビューに渡す
        $this->CommentTable->deleteComments($thread_id);
    }

    public function createComment() {
        // POSTデータを取得
        $comment = $this->request->getData();
        // 処理結果をビューに渡す
        $this->CommentTable->createComment($comment);

        //$this->set('data', $this->request->getData());
        return $this->redirect("/comments/{$comment['thread_id']}");
        //$routes->connect('/comments/:id', ['controller' => 'Comment', 'action' => 'comment'], ['pass' => ['id']]);
        // return $this->redirect('/thread/{$comment["thread_id"]}');
    }
/*
    public function deleteCommentUser($id, $thread_id)
    {
        // 処理結果をビューに渡す
        $this->CommentTable->deleteComment($id);
        $this->CommentsgoodTable->deleteCommentgood($id);
        return $this->redirect("/users/index/comments/$thread_id");
    }
    */

    public function goodindexuser($comment_id){
        $usersController = new UsersController();
        
        if($usersController->getlist()){
            $user = json_decode($usersController->getlist());
            $comment = json_decode($this->CommentTable->getData($comment_id));
            $this->CommentsgoodTable->commentgood($comment->id, $comment->thread_id, $user->id);
        }
        else{
            dd("no user");
        }
        $this->response->withType('text/plain');

        $connection = ConnectionManager::get('default');
        $query = $connection->newQuery();
        $totalAmount = $query
            ->select(['totalcomment_id' => $query->func()->count('comment_id')])
            ->from('comments_good')
            ->where(['comment_id' => $comment_id])
            ->execute()
            ->fetch('assoc')['totalcomment_id'];

        $commentcount = $this->CommentTable->get($comment_id);
        $commentcount->good_count = $totalAmount;
        $this->CommentTable->save($commentcount);
        return $this->response->withStringBody($totalAmount);
    }

/*　ボタンを押したら無限にカウント　
    public function goodindex($id){

        $commentcount = $this->CommentTable->get($id);
        $commentcount->good_count++;
        $this->CommentTable->save($commentcount);
        $this->response->withType('text/plain');
        return $this->response->withStringBody($commentcount->good_count);
        //$this->response->body(json_encode(['count' => $commentcount->good_count]));
        
        $count = $this->Counts->find()->firstOrFail();
        $count->count++;
        $this->Counts->save($count);
        echo $count->count;
        
    }
*/
    
}
