<?php $this->assign('title', 'Results'); ?>

<div id="index">
</div>

<?= $this->Html->scriptStart(['block' => true]); ?>
    var data = {
        cases: <?= json_encode($cases); ?>
    };
<?= $this->Html->scriptEnd(); ?>
<!--?= $this->Html->script('dist/results-bundle', ['block' => 'script']); ?-->

<?php debug($results) ?>
