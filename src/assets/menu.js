
//Menu Toogle on click

import Cookies from "js-cookie";

var ul = document.querySelector("#nav-accordion").querySelectorAll(".sub-menu")

ul.forEach(e=>
    e.querySelector("a").addEventListener("click", function Submenu() {
        if (e.classList.contains("active")){
            e.classList.remove("active")
        }
        else{
            e.classList.add("active")
        }
    }, false)
)

//Sidebar toggle

var toggle = document.querySelector(".sidebar-toggle-box")
var menu = document.querySelector("#sidebar")
var container = document.querySelector(".body")

if (Cookies.get('menu')) {
    menu.classList.replace('notHidden', Cookies.get('menu'));
    menu.classList.contains('hidden') ? container.classList.add('col-11') : container.classList.remove('col-11');
} else {

}
var toogleEvent = function () {
    menu.classList.contains('hidden') ? menu.classList.remove('hidden') : menu.classList.add('hidden');
    menu.classList.contains('hidden') ? container.classList.add('col-11') : container.classList.remove('col-11');
    if (menu.classList.contains('hidden')) {
        Cookies.set('menu', 'hidden')
    } else {
        Cookies.set('menu', '')
    }
};

function myFunction(x) {
    if (x.matches) { // If media query matches
        menu.classList.add('hidden');
    } else {
    }
}

var x = window.matchMedia("(max-width: 700px)");
myFunction(x); // Call listener function at run time
x.addListener(myFunction); // Attach listener function on state changes
toggle.addEventListener('click', toogleEvent);