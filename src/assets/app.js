/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

//Import CookiesJS
import Cookies from 'js-cookie';

//A2lix SF collection
import a2lix_lib from '@a2lix/symfony-collection/src/a2lix_sf_collection';

a2lix_lib.sfCollection.init({
    collectionsSelector: 'form div[data-prototype]',
    manageRemoveEntry: true,
    lang: {
        add: 'Add',
        remove: 'Remove'
    }
})

//Menu Toogle on click

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


// toggle.addEventListener("click", function toogleMenu(){
//     if (menu.classList.contains("hidden")){
//         menu.classList.remove("hidden")
//         container.classList.remove("col-12")
//         container.classList.add("col-10")
//         container.classList.remove("large")
//     }
//     else{
//         menu.classList.add("hidden")
//         container.classList.add("col-12")
//         container.classList.remove("col-10")
//         container.classList.add("large")
//     }
// })








