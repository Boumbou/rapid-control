
window.addEventListener("DOMContentLoaded", (event) => {
    const navList = [...document.getElementsByClassName("nav-link")]

    navList.forEach(elt => {
        if (window.location.href === elt.href) {
            elt.className = elt.className + " active"
        } else {
            elt.className = elt.className.replace("active", "")
        }
    });
})