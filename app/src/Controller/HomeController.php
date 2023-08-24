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
use Cake\Http\Client;
use Cake\Cache\Cache;
use App\Service\WeatherApiService;
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
        $this->CommentsgoodTable = TableRegistry::getTableLocator()->get("commentsgood");//アンダースコア(_)いらない
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
        $cacheKey = 'posts_data';

        // キャッシュからデータを読み込む
        $cachedData = Cache::read($cacheKey);
        if (!$cachedData) {
            // キャッシュが存在しない場合、データベースから取得
            $data = $this->ThreadsTable->find()->toArray();
            //dd($data);
            // データをキャッシュに書き込む
            Cache::write($cacheKey, $data, 'default');
            
        }else {
            // キャッシュにデータが存在する場合の処理
            // キャッシュからデータを使用
            $data = $cachedData;
        }
        //dd($data);
        // キャッシュされたデータまたはデータベースから取得したデータを使用
        //$this->set('posts', $posts);
        
        $title = "掲示板";
        $this->set('threads',$data);
        $this->set('title',$title);

        //$weather_icon = $data['weather'][0]['icon'];
        //$temperature = $data['main']['temp'] - 273.15;
        //dd($weathers['list']);
        //dd(date('m/d', $weathers['list'][0]['dt']));
        //$this->set(compact('weather_icon','PlaceDescription','weatherDescription', 'temperature'));
        
        $this->set('weathers',(new WeatherApiService())->getWeathers());
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
        $this->CommentsgoodTable->deleteCommentgood($id);
        $cacheKey = 'posts_data';
        Cache::delete($cacheKey, 'default'); // キャッシュの削除

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

        $cacheKey = 'posts_data';
        Cache::delete($cacheKey, 'default'); // キャッシュの削除
        return $this->redirect(['controller' => 'Home', 'action' => 'index']);
    }
    
}
