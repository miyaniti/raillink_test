<?= $this->Html->css('home.css') ?>
<h1><?= $title ?></h1>

<?php foreach($threads as $thread): ?>
  <table>
    <th class="thread"><a href="/thread/<?= $thread->id ?>/"><?= $thread->thread ?></a></th> 
    <td class="removebotton"><?= $this->Form->postLink('削除', 
      ['controller' => 'Home', 'action' => 'deletes', $thread->id],
      ['confirm' => '本当に削除しますか？']); ?>
    </td>
</table>
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
