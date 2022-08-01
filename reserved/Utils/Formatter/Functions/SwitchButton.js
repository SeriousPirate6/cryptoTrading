var bool = false

function App() {}

App.prototype.setState = function (state) {
    localStorage.setItem("checked", state)
}

App.prototype.getState = function () {
    return localStorage.getItem("checked")
}

function init(seconds) {
    var app = new App()
    var state = app.getState()
    var checkbox = document.querySelector("#test")

    if (state == "true") {
        checkbox.checked = true
        document.getElementById("demo").innerHTML = "DYNAMIC " + seconds + "s"
        setInterval(foo, seconds * 1000)
    } else {
        document.getElementById("demo").innerHTML = "STATIC"
    }

    checkbox.addEventListener("click", function () {
        app.setState(checkbox.checked)
    })
}

init(seconds)

function foo() {
    if (bool) return
    console.log("RUNNING")
    location.reload()
}

document.addEventListener("DOMContentLoaded", function () {
    var checkbox = document.querySelector('input[type="checkbox"]')

    checkbox.addEventListener("change", function () {
        if (checkbox.checked) {
            document.getElementById("demo").innerHTML = "DYNAMIC " + seconds + "s"
            location.reload()
            console.log("Checked")
        } else {
            document.getElementById("demo").innerHTML = "STATIC"
            bool = true
            console.log("Not checked")
        }
    })
})
