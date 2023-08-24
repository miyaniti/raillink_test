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
class UsersController extends AppController
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
        $this->Auth = $this->loadComponent('Auth');
        parent::initialize();

        $this->ThreadsTable = TableRegistry::getTableLocator()->get("threads");
        $this->CommentTable = TableRegistry::getTableLocator()->get("comments");
        $this->UsersTable = TableRegistry::getTableLocator()->get("users");
        $this->CommentsgoodTable = TableRegistry::getTableLocator()->get("commentsgood");//アンダースコア(_)いらない
        
    }

    public function index()
    {
        $title = "掲示板";
        $this->set('threads',$this->ThreadsTable->getList());
        if ($this->Auth->user()) {
            $this->set('loggedInUser', $this->Auth->user());
            $userObject = json_decode(json_encode($this->Auth->user()));
            $this->set('users', $this->UsersTable->getUser($userObject->user_name));
            //dd($this->UsersTable->getUser($userObject->user_name)   );
        }
        //$this->set('users',$this->UsersTable->getUser($user_name));
        $this->set('title',$title);
    }

    public function thread($id)
    {
        $this->set('title',$title);
    }


    public function login() {
        //$this->Auth = $this->loadComponent('Auth');
        $login_name = $this->request->getSession()->read('param1');
        $login_pw = $this->request->getSession()->read('param2');
        $user = $this->UsersTable->findByUsername($login_name)->first();
        if ($user && $user->pw === $login_pw) {
            // ログイン成功
            $this->request->getSession()->write('Auth.User', ['user_name' => $user->user_name]);
            $this->Flash->success('ログイン成功しました');
            //$this->loggedInUser = $this->Auth->user();
            //dd($this->loggedInUser);
            return $this->redirect(['controller' => 'Users', 'action' => 'index' ]);
            //$this->request->getSession()->write('clear_text', true);
            //$token = Security::hash($user->user_name . 'your-secret-key', 'sha256'); // ハッシュ化
            //$url = Router::url(['controller' => 'Users', 'action' => 'profile', '?' => ['token' => $token]], true);
            //$this->Flash->success('ログイン成功しました');
            //return $this->redirect(['controller' => 'User', 'action' => 'index', '?' => ['token' => $token], $user->user_name , true ]);
        } else {
            // ログイン失敗
            $this->Flash->error('ユーザー名またはパスワードが正しくありません。');
            return $this->redirect(['controller' => 'Home', 'action' => 'index']);
        }
        
    }

    public function getlogin(){
        return $this->Auth->user();
    }

    public function getlist(){
        if($this->Auth->user()){
            $userObject = json_decode(json_encode($this->Auth->user()));
            //dd($this->UsersTable->getlist($userObject->user_name));
            return $this->UsersTable->getlist($userObject->user_name);
        }
    }

    public function logout()
    {
        //dd($this->loadComponent('Auth'));
        // ログアウト処理
        //$this->loadComponent('Auth');
        // セッションを削除
        $this->getRequest()->getSession()->destroy();
        //$this->getRequest()->getSession()->delete('Auth');

        //$this->request->getSession()->delete('Auth.User');
        //dd(debug($this->getRequest()->getSession()->read()));
        // リダイレクトなどの処理を行う
        $this->Flash->success('ログアウトしました。');
        return $this->redirect(['controller' => 'Home', 'action' => 'index']);
    }

    public function usersubmitForm($user_id){
        $thread = $this->request->getData();
        // 処理結果をビューに渡す
        $this->ThreadsTable->createThredsUser($thread, $user_id);
        
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }

    
    public function deletes($id)
    {
        // 処理結果をビューに渡す
        $this->ThreadsTable->deleteThread($id);
        $this->CommentTable->deleteComments($id);
        $this->CommentsgoodTable->deleteCommentgood($id);

        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }
    

}
