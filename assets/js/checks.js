
const editBtn = document.querySelector('.btn.edit');
const hapusBtn = document.querySelector('.btn.hapus');


let once = false;

document.querySelector('#search-input').addEventListener('input', function(e){
    once = false;
});

document.body.addEventListener('click', function(e){

    if(e.target.id === 'select-all-checks'){
        const checks = document.querySelectorAll('.checks');
        e.target.addEventListener('input', function(e){
            if(e.target.checked){
                checks.forEach(check => {
                    check.checked = true;
                });
            }else{
                checks.forEach(check => {
                    check.checked = false;
                });
            }
        });
    }
    

    if(e.target.classList.contains('checks')){
        if(once) return;
        once = true;
        const checks = document.querySelectorAll('.checks');
        const selectAllCheck = document.querySelector('#select-all-checks');
        console.log('hweojroaew');
        checks.forEach(check => check.addEventListener('input', function(){
            console.log('in input');
            if(document.querySelectorAll('.checks:checked').length === checks.length){
                selectAllCheck.checked = true;
            }else{
                selectAllCheck.checked = false;
            }
        }));
    }
    
    
    if(e.target.classList.contains('edit')){
        const checkedForm = document.querySelector('#checked_form');
        if(checkedForm){
            checkedForm.action = './edit.php';
            checkedForm.submit();
        }
    }
    
    if(e.target.classList.contains('hapus')){
        const checkedForm = document.querySelector('#checked_form');
        let c = confirm("Yakin menghapus?");
        if(c && checkedForm){
            checkedForm.action = './hapus.php';
            checkedForm.submit();
        }
    }
})




