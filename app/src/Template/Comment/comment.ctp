<h1><a href="/">トップへ</a>

<?php foreach($threads as $thread): ?>
<h2><?= $thread->thread ?></a></h2>
<?php endforeach; 

?>

<?php foreach($comments as $comment): ?>
<h3><?= $comment->user_name ?></h3>
<p><?= $comment->comment ?></p>
<?php endforeach; 
?>


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
        'url' => ['controller' => 'Comment', 'action' => 'createComment']
        ]) 
    ?> 

    <?= $this->Form->control( 'user_name', [
        'type' => 'text',  
        'label' => 'ユーザー名',  
        'div' => false,
        'maxlength' => '100',
        'class'=>'comment'
        ])
    ?>

    <?= $this->Form->control( 'comment', [
        'type' => 'textarea',  
        'label' => 'コメント',  
        'div' => false,
        'maxlength' => '1000',
        'class'=>'comment'
        ])
    ?>

    <?= $this->Form->control( 'thread_id', [
        'type' => 'hidden',  
        'value' => $thread_id,
        ])
    ?>

  <!-- コントロールを配置 -->
  <?= $this->Form->submit('送信する') ?>

  <!-- フォームの終了 -->
  <?= $this->Form->end() ?>
</html>
