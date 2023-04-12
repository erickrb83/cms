<?php 
use Core\FH;
$this->start('head'); ?>
<script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
<?php $this->end(); ?>
<style>
    .ck-editor__editable_inline{
        min-height: 400px;
    }
    .is-invalid + .ck-editor .ck.ck-editor__main > .ck-editor__editable:not(.ck-focused){
        border-color:crimson;
    }
</style>
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
            <script>
                window.addEventListener('load', function () {
                    ClassicEditor
                        .create(document.querySelector('#body'))
                        .catch(error => {
                            console.error(error);
                        });
                });
                    
            </script>
        </div>
        <div class="text-right">
            <a href="<?=ROOT?>admin/articles" class="btn btn-secondary">Cancel</a>
            <input type="submit" class="btn btn-primary" value="Save"/>
        </div>
    </form>
</div>

<?php $this->end(); ?>