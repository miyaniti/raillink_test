<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Controller\Controller;
use Cake\Utility\Security;
use Cake\Routing\Router;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class HomeController extends AppController
{
    //public $components = ['Auth'];
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
        $this->UsersTable = TableRegistry::getTableLocator()->get("users");

        /*
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => ['user_name' => 'user_name', 'pw' => 'pw']
                ]
            ],
            'loginAction' => [
                'controller' => 'Home',
                'action' => 'index'
            ],
            'authError' => 'ユーザー名またはパスワードが正しくありません。',
            'loginRedirect' => [
                'controller' => 'User',
                'action' => 'index',
                'home'
            ],
            'unauthorizedRedirect' => $this->referer()
        ]);
        */
        
        
    }

    public function index()
    {
        $title = "掲示板";
        $this->set('threads',$this->ThreadsTable->getList());
        $this->set('title',$title);
    }

    public function thread($id)
    {
        $this->set('title',$title);
    }

    /*
    public function deleteThread($id)
    {
        // 処理結果をビューに渡す
        $this->ThreadsTable->deleteThread($id);
        
        return $this->redirect(['controller' => 'Home', 'action' => 'index']);
    }
    */

    public function deletes($id)
    {
        // 処理結果をビューに渡す
        $this->ThreadsTable->deleteThread($id);
        $this->CommentTable->deleteComments($id);
        return $this->redirect(['controller' => 'Home', 'action' => 'index']);
    }

    /*
    public function login()
    {
        dd("ok");
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }
    }
    */
    

    
    public function login() {
        //$this->Auth = $this->loadComponent('Auth');
        $login_name = $this->request->getData('user_name');
        $login_pw = $this->request->getData('pw');
        if ($this->request->is('post')) {
            $this->request->getSession()->write('param1', $login_name);
            $this->request->getSession()->write('param2', $login_pw);
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            /*
            $user = $this->UsersTable->findByUsername($this->request->getData('user_name'))->first();
            if ($user && $user->pw === $this->request->getData('pw')) {
                // ログイン成功
                $this->request->getSession()->write('Auth.User', ['user_name' => $user->user_name]);
                $this->Flash->success('ログイン成功しました');
                $loggedInUser = $this->Auth->user();
                dd($loggedInUser);
                //return $this->redirect(['controller' => 'User', 'action' => 'login']);
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
            */
        }
    }
    


    public function submitForm() {
        // POSTデータを取得
        $thread = $this->request->getData();
        
        // 処理結果をビューに渡す
        $this->ThreadsTable->createThreds($thread);
        
        return $this->redirect(['controller' => 'Home', 'action' => 'index']);
    }
    
}
