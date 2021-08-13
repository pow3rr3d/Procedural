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


//Custom JS
import './sortable';
import './menu';


//Console.log Style
console.log('%c Welcome to Procedural v0.0.1', 'color:#4ECDC4; background: #222; font-size: 40px; text-align: center; padding: 20px')






