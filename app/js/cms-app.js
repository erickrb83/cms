window.addEventListener('load', function () {
    ClassicEditor
        .create(document.querySelector('#body'))
        .catch(error => {
            console.error(error);
        });
});     

function deleteArticle(id){
    if(window.confirm("Are you sure you want to delete this article")){
        window.location.href = `<?=ROOT?>admin/deleteArticle/${id}`
    }
}

function deleteCategory(categoryId){
    if(window.confirm('Are you sure you want to delete this category?')){
        window.location.href = `<?=ROOT?>admin/deleteCategory/${categoryId}`;
    }
}

function confirmDelete(userId){
    if(window.confirm("Are you sure you want to delete the user?")){
        window.location.href = `<?=ROOT?>admin/deleteUser/${userId}`;
    }
}

function pager(page) {
    document.getElementById('p').value = page;
    var form = document.getElementById('pager');
    form.submit();
}