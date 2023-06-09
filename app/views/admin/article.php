<?php 
use Core\FH;
$this->start('head'); ?>
<?php $this->end(); ?>

<?php $this->start('content'); ?>
<h2><?= $this->heading;?></h2>
<div class="poster">
    <form method="POST" enctype="multipart/form-data">
        <?= FH::csrfField(); ?>
        <div class="row">
            <?= FH::inputBlock('Title', 'title', $this->article->title, ['class' => 'form-control'], ['class' => 'form-group col-md-8'], $this->errors) ;?>
            <?= FH::selectBlock('Status', 'status', $this->article->status, $this->statusOptions ,['class' => 'form-control'], ['class' => 'form-group col-md-2'], $this->errors); ?>
            <?= FH::selectBlock('Category', 'category_id', $this->article->category_id, $this->categoryOptions ,['class' => 'form-control'], ['class' => 'form-group col-md-2'], $this->errors); ?>
            <?= FH::textarea('Article Body', 'body', html_entity_decode($this->article->body), ['class' => 'form-control', 'rows' => '15'], ['class' => 'form-group col-md-12'], $this->errors); ?>
            <?= FH::fileUpload('Featured Image', 'featured_image', ['class' => 'form-control-file'], ['class' => 'form-group col-12'], $this->errors );?>
        </div>
        <?php if($this->hasImage) : ?>
            <div class="d-flex align-items-center">
                <h5 class="mr-2">Current Image</h5>
                <img src="<?=ROOT . $this->article->img?>" style="height:75px; width:75px; object-fit:cover;"/>
            </div>
        <?php endif;?>
        <div class="text-right">
            <a href="<?=ROOT?>admin/articles" class="btn btn-secondary">Cancel</a>
            <input type="submit" class="btn btn-primary" value="Save"/>
        </div>
    </form>
</div>

<?php $this->end(); ?>