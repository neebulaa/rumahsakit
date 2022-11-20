const searchInput = document.querySelector('#search-input');
const tableEl = document.querySelector('#table-element');

searchInput.addEventListener('input', async function(e){
    let table = e.target.dataset.table;
    tableEl.innerHTML = await fetch(`../../assets/php-searching-tables/${table}.php?search=${e.target.value}`).then(res => res.text());
});