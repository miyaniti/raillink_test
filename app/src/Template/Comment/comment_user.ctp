<?= $this->Html->script('jquery-3.7.0.min.js') ?>
<?= $this->Html->script('ajax_example.js') ?>

<?= $this->Html->css('home.css') ?>
<h1><a href="users/index">トップへ</a>



<?php foreach($threads as $thread): ?>
<h2><?= $thread->thread ?></a></h2>
<?php endforeach;?>

<?php if (!empty($user_name)): ?>
    <h5 class="login_user">ログイン中： <?= $user_name ?></h5 class="login_user">
<?php else: ?>
    <p>Not logged in</p>
<?php endif; ?>

<?php foreach($comments as $comment): ?>
<h3 class="user_name"><?= $comment->user_name ?></h3>
<div class="comment">
    <p class="comments"><?= $comment->comment ?> </p>
    <p class="comments" id="comment<?=$comment->id?>"><?= $comment->good_count ?></p>
    <p><?= $this->Form->button('いいね', ['class' => 'border_btn','type' => 'button', 'data-id' => $comment->id]
    )?></p>
    <!--<p class="border_btn08"> <span>いいね</span></p> !-->
    <?php if ($comment->user_id === $user_id ): ?>
        <p class="deletecomment">
        <?= $this->Form->postLink('削除', 
        ['controller' => 'Comment','action' => 'deleteCommentUser', $comment->id, $comment->thread_id],
        ['confirm' => '本当に削除しますか？']); ?>
        </p>
    <?php endif; ?>
</div>
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
        'url' => ['controller' => 'Comment', 'action' => 'createCommentUser']
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
        'value' => $user_name,
        ])
    ?>
    <?= $this->Form->control( 'user_id', [
        'type' => 'hidden',  
        'value' => $user_id,
        ])
    ?>

  <!-- コントロールを配置 -->
  <?= $this->Form->submit('送信する') ?>

  <!-- フォームの終了 -->
  <?= $this->Form->end() ?>
</html>
