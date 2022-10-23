
document.addEventListener("DOMContentLoaded", (event) => {

    const centerList = [...document.getElementsByClassName("bi-trash")];
    const jsUser = document.getElementById("js-user");
    const isAuth = jsUser.dataset.isAuthenticated;

    centerList.forEach(elt => {
        elt.addEventListener("click", async (event) => {
            event.preventDefault();
            const centerId = elt.dataset.center;
            const response = await axios.post(elt.href, { id: centerId }).then(function (response) {
                console.log(response)
                return JSON.parse(response.status)
            })

            if (response === 200) {
                elt.parentElement.parentElement.parentElement.parentElement.remove()
                const centerListCount = [...document.getElementsByClassName("bi-trash")].length
                if (centerListCount > 1) {
                    document.getElementById("header-text").innerText = (centerList.length - 1) + "centres enregistrés"
                } else if (centerListCount > 0) {
                    document.getElementById("header-text").innerText = "1 centre enregistré"
                } else {
                    document.getElementById("header-text").innerText = "Vous n'avez pas encore enregistré de centres"
                }
            } else {

            }

        })
    });

})