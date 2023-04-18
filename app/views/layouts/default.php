<?php use CORE\{Config, Session}; ?>

<?php $this->partial('partials/head');?>

<body>
    <?php $this->partial('partials/mainMenu');?>
    <div class="container-fluid p-4">
        <?= Session::displaySessionAlert(); ?>
        <?php $this->content('content'); ?>
    </div>
</body>
</html>