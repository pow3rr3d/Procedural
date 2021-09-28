
// vars
import a2lix_lib from "@a2lix/symfony-collection";

var xhr = new XMLHttpRequest()
var input = document.querySelector("#searchInput")
var div = document.querySelector("#searchResult")

// clean the modal
function clearner() {
    document.getElementById("user").classList.add("hidden")
    document.getElementById("user").innerHTML = ''
    document.querySelector("#userTitle").classList.add("hidden")
    document.getElementById("company").innerHTML = ''
    document.getElementById("company").classList.add("hidden")
    document.querySelector("#companyTitle").classList.add("hidden")
    document.getElementById("process").innerHTML = ''
    document.getElementById("process").classList.add("hidden")
    document.querySelector("#processTitle").classList.add("hidden")
}

// Toggle modal
export function open() {
    let modal = document.querySelector("#modalSearch")

    document.addEventListener("keydown", function (e) {
        if (e.ctrlKey && e.shiftKey) {
            function modalDisplay() {
                clearner()
                input.value = ""
                div.classList.add("hidden")
                $(modal).modal('show');
            }

            modalDisplay()
        }
    })
}

//Research


input.addEventListener("change", function (event) {
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
            div.innerHTML = "                        " +
                "<h3 id=\"userTitle\" class=\"s\">(language !== 'fr' ? 'Users' : 'Utilisateurs')</h3>\n" +
                "                        <ul id=\"user\">\n" +
                "\n" +
                "                        </ul>\n" +
                "                        <h3 id=\"companyTitle\" class=\"hidden\">(language !== 'fr' ? 'Companies' : 'Société')</h3>\n" +
                "                        <ul id=\"company\">\n" +
                "\n" +
                "                        </ul>\n" +
                "                        <h3 id=\"processTitle\" class=\"hidden\">(language !== 'fr' ? 'Processes' : 'Prodédures')</h3>\n" +
                "                        <ul id=\"process\">\n" +
                "\n" +
                "                        </ul>\n" +
                "                        <h3 id=\"stateTitle\" class=\"hidden\">(language !== 'fr' ? 'States' : 'États')</h3>\n" +
                "                        <ul id=\"state\">\n" +
                "\n" +
                "                        </ul>"
        }
    }

    div.classList.add("hidden")
    clearner()
    xhr.open("POST", "/search/index")
    xhr.responseType = 'text'
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.send(JSON.stringify({
        search: event.target.value
    }))
    xhr.onreadystatechange = function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            var results = $.parseJSON(xhr.responseText)
            if (typeof results !== "string") {
                div.classList.remove("hidden")
                for (var items in results) {
                    if (results[items].user) {
                        document.querySelector("#userTitle").classList.remove("hidden")
                        document.getElementById("user").classList.remove("hidden")
                        var x = document.createElement("li")
                        var t = document.createTextNode("[" + results[items].user.id + "]" + " " + results[items].user.name + " " + results[items].user.surname)
                        var a = document.createElement("a")
                        if (results[0].role.value[0] === "ROLE_ADMIN") {
                            a.setAttribute('href', "/user/" + results[items].user.id)

                        } else {
                            a.setAttribute('href', "/account/" + results[items].user.id)
                        }
                        a.appendChild(x)
                        x.appendChild(t)
                        document.getElementById("user").appendChild(a)
                    }
                    if (results[items].company) {
                        document.querySelector("#companyTitle").classList.remove("hidden")
                        document.getElementById("company").classList.remove("hidden")
                        var a = document.createElement("a")
                        a.setAttribute('href', "/companies/" + results[items].company.id)
                        var x = document.createElement("li")
                        var t = document.createTextNode("[" + results[items].company.id + "]" + " " + results[items].company.name)
                        a.appendChild(x)
                        x.appendChild(t)
                        document.getElementById("company").appendChild(a)
                    }
                    if (results[items].process) {
                        document.querySelector("#processTitle").classList.remove("hidden")
                        document.getElementById("process").classList.remove("hidden")
                        var x = document.createElement("li")
                        var t = document.createTextNode("[" + results[items].process.id + "]" + " " + results[items].process.name)
                        var a = document.createElement("a")
                        if (results[0].role.value[0] === "ROLE_ADMIN") {
                            a.setAttribute('href', "/processes/" + results[items].process.id)
                            a.appendChild(x)
                            x.appendChild(t)
                            document.getElementById("process").appendChild(a)
                        } else {
                            x.appendChild(t)
                            document.getElementById("process").appendChild(t)
                        }

                    }
                    if (results[items].state) {
                        document.querySelector("#stateTitle").classList.remove("hidden")
                        document.getElementById("state").classList.remove("hidden")
                        var x = document.createElement("li")
                        var t = document.createTextNode("[" + results[items].state.id + "]" + " " + results[items].state.name)
                        var a = document.createElement("a")
                        if (results[0].role.value[0] === "ROLE_ADMIN") {
                            a.setAttribute('href', "/state/" + results[items].state.id)
                            a.appendChild(x)
                            x.appendChild(t)
                            document.getElementById("state").appendChild(a)
                        } else {
                            x.appendChild(t)
                            document.getElementById("state").appendChild(t)
                        }

                    }
                    if (results[items].message) {
                        div.classList.remove("hidden")
                        div.innerHTML = results[items].message

                    }
                }
            }
        }
    }
 })