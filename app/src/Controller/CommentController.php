<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Controller\Controller;

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
        //dd($id);
        $this->set('threads',$this->ThreadsTable->getThreds($id));
        $this->set('comments',$this->CommentTable->getComment($id));
        $this->set('thread_id', $id);
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
        return $this->redirect("/thread/$thread_id");
    }

    public function createComment() {
        // POSTデータを取得
        $comment = $this->request->getData();
        // 処理結果をビューに渡す
        $this->CommentTable->createComment($comment);

        //$this->set('data', $this->request->getData());
        return $this->redirect("/thread/{$comment['thread_id']}");

        // return $this->redirect('/thread/{$comment["thread_id"]}');
    }

}
