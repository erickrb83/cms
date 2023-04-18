<?php use CORE\{Config, Session}; ?>

<?php $this->partial('partials/head');?>

<body>
    <?php $this->partial('partials/mainMenu');?>
    <div class="d-flex">
        <?php $this->partial('partials/adminMenu');?>
        <div class="container-fluid p-4">
            <?= Session::displaySessionAlert(); ?>
            <?php $this->content('content'); ?>
        </div>
    </div>
    
</body>
</html>