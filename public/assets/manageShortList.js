
document.addEventListener("DOMContentLoaded", () => {

    const listContainer = document.getElementById("result-list-container");
    listContainer.addEventListener("change", () => {
        const saveButtons = document.getElementsByClassName("add-to-shortlist");
        s

        saveButtons.addEventListener("click", (event) => {
            event.preventDefault();
            const isAuth = document.getElementById("js-user").dataset.isAuthenticated;
            var user = JSON.parse(userRating.dataset.user);

            console.log("clicked")

        })
    })


})