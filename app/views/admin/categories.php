<?php $this->start('content'); ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h2>Categories</h2>
    <a href="<?=ROOT?>admin/category/new" class="btn btn sm btn-primary">New Category</a>

</div>
<div class="poster">
    <table class="table table-hover table-stripped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->categories as $category) : ?>
                <tr>
                    <td><?= $category->id    ?></td>
                    <td><?= $category->name ?></td>
                    <td class ="text-right">
                        <a href="<?=ROOT ?>admin/category/<?=$category->id?> " class="btn btn-sm btn-info">Edit</a>
                        <button class="btn btn-sm btn-danger" onclick="deleteCategory('<?=$category->id?>')">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php $this->partial('partials/pager')?>
</div>

<?php $this->end();?>