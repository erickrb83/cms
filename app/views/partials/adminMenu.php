<?php 
    use Core\H;
    use App\Models\Users;
    global $currentUser;
?>

<ul class="side-nav">
    <li class="nav-title">Author Portal</li>
    <?=H::navItem('admin/articles', 'Articles');?>
    <?php 
        if($currentUser->hasPermission('admin')){
            echo H::navItem('admin/categories', 'Catagories');
            echo H::navItem('admin/Users', 'Users');
        }
    ?>
    
</ul>