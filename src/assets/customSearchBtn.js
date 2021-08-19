let btn = document.querySelector(".custom-search-btn")
let search = document.querySelector("form.hidden")

btn.addEventListener("click", function (){
    search.classList.contains('hidden') ? search.classList.remove('hidden') : search.classList.add('hidden');
})
