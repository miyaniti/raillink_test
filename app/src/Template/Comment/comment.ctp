<?= $this->Html->script('jquery-3.7.0.min.js') ?>
<?= $this->Html->script('ajax_example.js') ?>
<?= $this->Html->css('home.css') ?>

<h1><a href="/">トップへ</a>
<?php foreach($threads as $thread): ?>
<h2><?= $thread->thread ?></a></h2>
<?php endforeach; 
?>

<?php foreach($comments as $comment): ?>
<h3 class="user_name">ユーザー名：<?= $comment->user_name ?></h3>
<div class="comment">
    <p class="comments">コメント：<?= $comment->comment ?> </p>
    <p class="comments" id="comment<?=$comment->id?>"><?= $comment->good_count ?></p>
    <!--<p><?php/* $this->Form->button('いいね', ['class' => 'border_btn','type' => 'button', 'data-id' => $comment->id])//一応残しておく*/?></p>!-->
    <?php if (!empty($users)): ?>
        <p><?= $this->Form->button('いいね', ['class' => 'border_btn','type' => 'button', 'data-id' => $comment->id])?></p>
    <?php else : ?>
        <p class="border_btn"> いいね </p> <p>(ログインするとカウントされるようになります)</p>
    <?php endif; ?>
    <?php if ($this->publicDeleteButton($comment,$users)): ?>
        <p class="deletecomment">
        <?= $this->Form->postLink('削除', 
        ['controller' => 'Comment','action' => 'deleteComment', $comment->id, $comment->thread_id],
        ['confirm' => '本当に削除しますか？']); ?>
        </p>
    <?php endif; ?>
</div>
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
<?php if (!empty($users)): ?>
<!-- フォームの作成 -->
<?= $this->Form->create(null,[
        'type' => 'post',
        'url' => ['controller' => 'Comment', 'action' => 'createComment']
        ]) 
    ?> 
    
    <div class="create_comment">
        <?= $this->Form->control( 'comment', [
            'type' => 'textarea',  
            'label' => 'コメント',  
            'div' => false,
            'maxlength' => '1000',
            'class'=>'comment'
            ])
        ?>
    </div>

    <?= $this->Form->control( 'thread_id', [
        'type' => 'hidden',  
        'value' => $thread_id,
        ])
    ?>
    <?= $this->Form->control( 'user_name', [
        'type' => 'hidden',  
        'value' => $users->user_name,
        ])
    ?>
    <?= $this->Form->control( 'user_id', [
        'type' => 'hidden',  
        'value' => $users->id,
        ])
    ?>

  <!-- コントロールを配置 -->
  <?= $this->Form->submit('送信する') ?>

  <!-- フォームの終了 -->
  <?= $this->Form->end() ?>
<?php else: ?>
  <!-- フォームの作成 -->
    <?= $this->Form->create(null,[
        'type' => 'post',
        'url' => ['controller' => 'Comment', 'action' => 'createComment']
        ]) 
    ?> 

    <div class="create_user">
        <?= $this->Form->control( 'user_name', [
        'type' => 'text',  
        'label' => 'ユーザー名',  
        'div' => false,
        'maxlength' => '100',
        'class'=>'comment'
        ])
        ?>
    </div>
    
    <div class="create_comment">
        <?= $this->Form->control( 'comment', [
            'type' => 'textarea',  
            'label' => 'コメント',  
            'div' => false,
            'maxlength' => '1000',
            'class'=>'comment'
            ])
        ?>
    </div>

    <?= $this->Form->control( 'thread_id', [
        'type' => 'hidden',  
        'value' => $thread_id,
        ])
    ?>

  <!-- コントロールを配置 -->
  <?= $this->Form->submit('送信する') ?>

  <!-- フォームの終了 -->
  <?= $this->Form->end() ?>
<?php endif; ?>
</html>
