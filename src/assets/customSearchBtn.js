let btn = document.querySelector(".custom-search-btn")
let search = document.querySelector("form.hidden")

if (btn !== null){
    btn.addEventListener("click", function (){
        search.classList.contains('hidden') ? search.classList.remove('hidden') : search.classList.add('hidden');
    })
}

