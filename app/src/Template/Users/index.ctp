<?= $this->Html->css('home.css') ?>
<h1><?= $title ?></h1>

<?php if (!empty($loggedInUser)): ?>
    <h5 class="login_user">ログイン中： <?= h($loggedInUser['user_name']) ?></h5 class="login_user">
<?php else: ?>
    <p>Not logged in</p>
<?php endif; ?>

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
<?php foreach($threads as $thread): ?>
<table>
    <?php if ($thread->user_id === $users->id || $thread->user_id === NULL): ?>
        <th class="thread"><a href="/users/index<?= $thread->getUrl() ?>"><?= $thread->thread ?></a></th> 
        <th class="datetime"><?= $thread->getdeta() ?></th>
        <td class="removebotton"><?= $this->Form->postLink('削除', 
        ['controller' => 'Users', 'action' => 'deleteThread', $thread->id],
        ['confirm' => '本当に削除しますか？']); ?>
    <?php else: ?>
        <th class="thread"><a href="<?= $thread->getUrl() ?>"><?= $thread->thread ?></a></th> 
        <th class="datetime"><?= $thread->getdeta() ?></th>
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
      'url' => ['controller' => 'Users', 'action' => 'usersubmitForm', $users->id]
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
