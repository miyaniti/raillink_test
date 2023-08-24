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
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class HomeController extends AppController
{
    public const CNT = 5; // 天気取得 
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

        /*天気情報取得*/
        //$http = new Client();
        $apiKey = 'd8c4abf2ef8ec7f50742ada4ead72ad6';
        $city = 'Tokyo';
        $apiUrl = "http://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$apiKey&cnt=". self::CNT; // cnt分データを取得
        $response = file_get_contents($apiUrl);
        $weathers = json_decode($response, true);
        //dd($weathers['list'][0]);
        $PlaceDescription = $this->convertToJapanesePlace($city);
        for($i = 0; $i < HomeController::CNT ;$i++){
            $weathers['list'][$i]['dt'] = date('m/d　H時', $weathers['list'][$i]['dt']);
            $weathers['list'][$i]['weather'][0]['id'] = $this->convertToJapaneseWeather($weathers['list'][$i]['weather'][0]['id']);
        }
        //$weather_icon = $data['weather'][0]['icon'];
        //$temperature = $data['main']['temp'] - 273.15;
        //dd($weathers['list']);
        //dd(date('m/d', $weathers['list'][0]['dt']));
        //$this->set(compact('weather_icon','PlaceDescription','weatherDescription', 'temperature'));
        
        $this->set('PlaceDescription', $PlaceDescription);
        $this->set('weathers',$weathers['list']);
    }

    function convertToJapanesePlace($place) {
        //dd($place);
        $placeMap = array(
            "Tokyo" => '東京',
            'Osaka' => '大阪',
            // 他のもここに追加
        );
        // マッピングが存在する場合は日本語に変換
        if (isset($placeMap[$place])) {
            return $placeMap[$place];
        } else {
            return 'その他の場所';
        }
    }

    function convertToJapaneseWeather($englishWeather) {
        //dd($englishWeather);
        $weatherMap = array(
            '200' => '小雨と雷雨',
            '201' => '雨と雷雨',
            '202' => '大雨と雷雨',
            '210' => '光雷雨',
            '211' => '雷雨',
            '212' => '重い雷雨',
            '221' => 'ぼろぼろの雷雨',
            '230' => '小雨と雷雨',
            '231' => '霧雨と雷雨',
            '232' => '重い霧雨と雷雨',
            '300' => '光強度霧雨',
            '301' => '霧雨',
            '302' => '重い強度霧雨',
            '310' => '光強度霧雨の雨',
            '311' => '霧雨の雨',
            '312' => '重い強度霧雨の雨',
            '313' => 'にわかの雨と霧雨',
            '314' => '重いにわかの雨と霧雨',
            '321' => 'にわか霧雨',
            '500' => '小雨',
            '501' => '適度な雨',
            '502' => '重い強度の雨',
            '503' => '非常に激しい雨',
            '504' => '極端な雨',
            '511' => '雨氷',
            '520' => '光強度のにわかの雨',
            '521' => 'にわかの雨',
            '522' => '重い強度にわかの雨',
            '531' => '不規則なにわかの雨',
            '600' => '小雪',
            '601' => '雪',
            '602' => '大雪',
            '611' => 'みぞれ',
            '612' => 'にわかみぞれ',
            '615' => '光雨と雪',
            '616' => '雨や雪',
            '620' => '光のにわか雪',
            '621' => 'にわか雪',
            '622' => '重いにわか雪',
            '701' => 'ミスト',
            '711' => '煙',
            '721' => 'ヘイズ',
            '731' => '砂、ほこり旋回する',
            '741' => '霧',
            '751' => '砂',
            '761' => 'ほこり',
            '762' => '火山灰',
            '771' => 'スコール',
            '781' => '竜巻',
            '800' => '晴天',
            '801' => '薄い雲',
            '802' => '雲',
            '803' => '曇りがち',
            '804' => '厚い雲',
            // 他の天気状態もここに追加
        );
    
        // マッピングが存在する場合は日本語に変換
        if (isset($weatherMap[$englishWeather])) {
            return $weatherMap[$englishWeather];
        } else {
            return 'その他の天気';
        }
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
