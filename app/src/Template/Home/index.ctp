<?= $this->Html->css('home.css') ?>
<h1><?= $title ?></h1>
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
  <?php foreach($threads as $thread): ?>
    <table>
      <th class="thread"><a href="<?= $thread->getUrl() ?>"><?= $thread->thread ?></a></th> 
      <th class="datetime"><?= $thread->getdeta() ?></th>
      <?php if ($thread->user_id === NULL): ?>
        <td class="removebotton"><?= $this->Form->postLink('削除', 
        ['controller' => 'Home', 'action' => 'deletes', $thread->id],
        ['confirm' => '本当に削除しますか？']); ?>
      <?php endif; ?>
    </table>
  <?php endforeach; ?>

 <!--
  <form method="post" name="login" class="login">
      <p class="login_text">username</p>
      <input type="text" name="username">
      <p class="login_text">password</p>
      <input type="password" name="password">
      <button type="submit" class="login_button" name="login">ログイン</button>
  </form>
 -->


<html>
<?php /*
    <form action="/controller/action" method="post">
        <div>
            <label for="name">新しいスレッドの作成</label>
            <input type="text" style="width:200px;" maxlength="128" id="thread" name="thread">
        </div>
        <input type="submit" value="作成する">
    </form>
*/ ?>

  <!-- フォームの作成 -->
  <?= $this->Form->create(null,[
      'type' => 'post',
      'url' => ['controller' => 'Home', 'action' => 'submitForm']
    ]) 
  ?>
  <div class="create_thread"> 
  <?= $this->Form->control( 'thread', [
      'type' => 'text',  
      'label' => '新しいスレッドの作成',  
      'div' => false,
      'maxlength' => '128',
      'class'=>'thread'
    ])
  ?>
</div>

  <!-- コントロールを配置 -->
  <div class = "create_botton"><?= $this->Form->submit('作成する') ?></div>

  <!-- フォームの終了 -->
  <?= $this->Form->end() ?>

</html>
<h2 class="weather">天気情報</h2>
<?php foreach($weathers as $weather): ?>
  <table class="weather-table">
    <tr>
          <!-- PHPコードで天気情報をループ表示 -->
      <th><?= $weather["place"] ?></th>
      <th><?= $weather["date"] ?></th>
      <th><img class="weather-icon" src="<?= $weather['img'] ?>" alt="Unknown"> </img></th>
      <th>天気：<?= $weather['weather'] ?></th>
      <th>気温：<?= $weather['temp'] ?></th>
    </tr>
  </table>
<?php endforeach; ?>
