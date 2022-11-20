
const selectAllCheck = document.querySelector('#select-all-checks');
const checkes = document.querySelectorAll('.checks');
const checkedForm = document.querySelector('#checked_form');
const editBtn = document.querySelector('.btn.edit');
const hapusBtn = document.querySelector('.btn.hapus');

selectAllCheck.addEventListener('input', function(e){
    if(selectAllCheck.checked){
        checkes.forEach(check => {
            check.checked = true;
        });
    }else{
        checkes.forEach(check => {
            check.checked = false;
        });
    }
});


checkes.forEach(check => check.addEventListener('input', function(){
    if(document.querySelectorAll('.checks:checked').length === checkes.length){
        selectAllCheck.checked = true;
    }else{
        selectAllCheck.checked = false;
    }
}));


editBtn.addEventListener('click', function(){
    checkedForm.action = './edit.php';
    checkedForm.submit();
});

hapusBtn.addEventListener('click', function(){
    let c = confirm("Yakin menghapus?");
    if(c){
        checkedForm.action = './hapus.php';
        checkedForm.submit();
    }
});

