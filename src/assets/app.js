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

let id = document.querySelector('.userid').value
let route = "/user/language/".concat(' ', id)
let language = 'en'
var xhr = new XMLHttpRequest()
xhr.open("Get", route)
xhr.responseType = 'text'
xhr.send()
xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
        let language = $.parseJSON(xhr.responseText)
        a2lix_lib.sfCollection.init({
            collectionsSelector: 'form div[data-prototype]',
            manageRemoveEntry: true,
            lang: {
                add: (language !== 'fr' ? 'Add' : 'Ajouter'),
                remove: (language !== 'fr' ? 'Remove' : 'Supprimer')
            }
        })
    }
}



//Custom JS
import './sortable';
import './menu';
import './customSearchBtn';
// import './search';

//import modal search
import {open as search} from './search';

search();

//Console.log Style
console.log('%c Welcome to Procedural v0.2', 'color:#4ECDC4; background: #222; font-size: 25px; text-align: center; padding: 10px')






