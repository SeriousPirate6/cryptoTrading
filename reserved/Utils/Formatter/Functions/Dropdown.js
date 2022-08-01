// GetElement for dropdown functions
function App() {}
App.prototype.setState = function (state) {
    localStorage.setItem("listItem", state)
}

App.prototype.setLoad = function (load) {
    localStorage.setItem("isLoaded", load)
}

App.prototype.getState = function () {
    return localStorage.getItem("listItem")
}

App.prototype.getLoad = function () {
    return localStorage.getItem("isLoaded")
}

var app = new App()
var state = app.getState()

var load = app.getLoad()

function list(element) {
    app.setState(element)
    document.getElementById("dropd").innerHTML = element
    app.setLoad(false)
    console.log(app.getLoad())
    location.reload()
}

// Pure dropdown funtions
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show")
}

window.onclick = function (event) {
    if (!event.target.matches(".dropbtn")) {
        var dropdowns = document.getElementsByClassName("dropdown-content")
        var i
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i]
            if (openDropdown.classList.contains("show")) {
                openDropdown.classList.remove("show")
            }
        }
    }
}
$(document).ready(function () {
    createCookie("gfg", state, "10")
})

// Function to create the cookie
function createCookie(name, value, days) {
    var expires

    if (days) {
        var date = new Date()
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000)
        expires = "; expires=" + date.toGMTString()
    } else {
        expires = ""
    }

    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/"
}

if (load === "false") {
    app.setLoad(true)
    console.log("NOT LOADED")
    setInterval(rld, 500)
}

function rld() {
    console.log("SECOND REFRESH...")
    location.reload()
}
