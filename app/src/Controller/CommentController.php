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
    public function initialize()
    {
        parent::initialize();

        $this->ThreadsTable = TableRegistry::getTableLocator()->get("threads");
        $this->CommentTable = TableRegistry::getTableLocator()->get("comments");
    }

    public function comment($id)
    {
        $this->set('threads',$this->ThreadsTable->getThreds($id));
        $this->set('comments',$this->CommentTable->getComment($id));
        $this->set('thread_id', $id);
    }

    public function commentUser($id)
    {
        $usersController = new UsersController();
        if($usersController->getlogin()){
            $user_name = $usersController->getlogin();
            $this->set('threads',$this->ThreadsTable->getThreds($id));
            $this->set('comments',$this->CommentTable->getComment($id));
            $this->set('thread_id', $id);
            /*ログイン情報取得 */
            $userlist = $usersController->getlist($user_name);
            $this->set('user_name', $userlist->user_name);
            $this->set('user_id', $userlist->id);
        }
        else{
            return $this->redirect("/comments/$id");
        }

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

    public function createCommentUser() {
        // POSTデータを取得
        $comment = $this->request->getData();
        // 処理結果をビューに渡す
        $this->CommentTable->createComment($comment);

        //$this->set('data', $this->request->getData());
        return $this->redirect("/users/index/comments/{$comment['thread_id']}");
        //$routes->connect('/comments/:id', ['controller' => 'Comment', 'action' => 'comment'], ['pass' => ['id']]);
        // return $this->redirect('/thread/{$comment["thread_id"]}');
    }
    public function deleteCommentUser($id, $thread_id)
    {
        // 処理結果をビューに渡す
        $this->CommentTable->deleteComment($id);
        return $this->redirect("/users/index/comments/$thread_id");
    }

    public function goodindex($id){
        /*
        $count = $this->Counts->find()->firstOrFail();
        $count->count++;
        $this->Counts->save($count);
        */
        $commentcount = $this->CommentTable->get($id);
        $commentcount->good_count++;
        $this->CommentTable->save($commentcount);

        $this->response->withType('text/plain');
        //$this->response->body(json_encode(['count' => $commentcount->good_count]));
        return $this->response->withStringBody($commentcount->good_count);
        /*
        $count = $this->Counts->find()->firstOrFail();
        $count->count++;
        $this->Counts->save($count);
        echo $count->count;
        */
    }
    
}
