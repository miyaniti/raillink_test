<h1><?= $title ?></h1>
<?php foreach($threads as $thread): ?>
<h2><a href="/thread/<?= $thread->id ?>/"><?= $thread->thread ?></a></h2>
<?php endforeach; ?>
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
  <?= $this->Form->control( 'thread', [
      'type' => 'text',  
      'label' => '新しいスレッドの作成',  
      'div' => false,
      'maxlength' => '128',
      'class'=>'thread'
    ])
  ?>

  <!-- コントロールを配置 -->
  <?= $this->Form->submit('作成する') ?>

  <!-- フォームの終了 -->
  <?= $this->Form->end() ?>
</html>
