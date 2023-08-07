<h1><?= $title ?></h1>
<?php foreach($threads as $thread): ?>
<h2><a href="/thread/<?= $thread->id ?>/"><?= $thread->thread ?></a></h2>
<?php endforeach; ?>