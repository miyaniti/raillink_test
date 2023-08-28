<!DOCTYPE html>
<html>
<head>
    <!-- ヘッドセクションのコード -->
</head>
<body>
    <header>
        <nav>
            <ul>
                <?php if ($this->request->getSession()->read('Auth.User')): /*if ($this->request->getSession()->check('Auth.User')):*/ ?>
                    <?= $this->Form->create(null, [
                            'url' => ['controller' => 'Users','action' => 'logout']
                            ]) 
                        ?>
                        <!-- ボタンを生成し、クリック時に確認ダイアログを表示 -->
                        <h5><?= $this->Form->button('ログアウト', [
                            'class'=> 'logout_button',
                            'confirm' => 'ログアウトしますか？'
                            ]); ?>
                        </h5>
                    <?= $this->Form->end() ?>
                    <h5 class="login_user">ログイン中： <?= $this->request->getSession()->read('Auth.User.user_name') ?></h5>
                    
                <?php else: ?>
                    <div class="top">
                    <!-- フォームの作成 -->
                        <?= $this->Form->create(null,[
                            'type' => 'post',
                            'class' => 'login',
                            'url' => ['controller' => 'Home', 'action' => 'login']
                            ]) 
                        ?>
                        <?= $this->Form->control( 'user_name', [
                            'type' => 'text',  
                            'label' => 'ユーザー名',  
                            'div' => false,
                            'maxlength' => '128',
                            'class'=>'login_text'
                        ]);
                        ?>
                            <?= $this->Form->control( 'pw', [
                            'type' => 'password',  
                            'label' => 'パスワード',  
                            'div' => false,
                            'maxlength' => '128',
                            'class'=>'login_text'
                            ])
                        ?>
                            <!-- コントロールを配置 -->
                            <?= $this->Form->submit('ログイン',[
                            'class' => "login_button"
                            ]) 
                            ?>
                        <!-- フォームの終了 -->
                        <?= $this->Form->end() ?>
                    </div>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </main>
    <footer>
        <!-- フッターのコード -->
    </footer>
</body>
</html>
