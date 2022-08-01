var coll = document.getElementsByClassName("collapsible")
var i

function namedListener() {
    this.classList.toggle("active")
    var content = this.nextElementSibling
    if (content.style.display === "block") {
        content.style.display = "none"
    } else {
        content.style.display = "block"
    }
}

for (i = 0; i < coll.length; i++) {
    coll[i].replaceWith(coll[i].cloneNode(true))
    coll[i].addEventListener("click", namedListener)
}
