
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
                console.log("deleted")
            } else {
                console.log("not deleted")
            }

        })
    });

})