

window.addEventListener("DOMContentLoaded", (event) => {
    //the event occurred.
    const btn = document.getElementById("search-button");
    const url = document.getElementById("search-button").href;
    const criteria = document.getElementById("search-criteria");

    btn.addEventListener("click", async (event) => {
        event.preventDefault();
        const listContainer = document.getElementById("result-list-container");
        const resultHeader = document.getElementById("result-header");
        const resultHeaderText = document.getElementById("result-header-text");
        const jsUser = document.getElementById("js-user");
        const isAuth = jsUser.dataset.isAuthenticated;
        const noResutBlock = document.getElementById("no-result");


        listContainer.innerHTML = ""
        if (!event.defaultPrevented) { return false }

        console.log(url);
        const response = await axios.post(url, { location: criteria.value }).then(function (response) {
            console.log(response)
            return JSON.parse(response.data)
        })
        const { data, shortlist } = response;
        console.log(data)

        if (data.nhits > 0) {
            resultHeaderText.innerText = `${data.nhits} centres trouvés...`;
            resultHeader.className = resultHeader.className.replace("d-none", "")
            if (!noResutBlock.className.indexOf("d-none") > 0) {
                noResutBlock.className = noResutBlock.className + " d-none";
            }
        } else {
            resultHeaderText.innerText = "Aucun résultat pour votre recherche...";
            if (!resultHeader.className.indexOf("d-none") > 0) {
                resultHeader.className = resultHeader.className + "d-none";
            } else {
                noResutBlock.className = noResutBlock.className.replace("d-none", "");
            }
        }


        data.records.forEach(element => {
            const cardDiv = document.createElement("div");
            cardDiv.setAttribute("class", "col-sm-12 col-md-8 card border-secondary mb-3")
            const cardBody = document.createElement("div");
            cardBody.setAttribute("class", "card-body");
            cardDiv.appendChild(cardBody);
            const cardTitle = document.createElement("h4");
            cardTitle.setAttribute("class", "card-title");
            const cardText = document.createElement("p");
            cardText.setAttribute("class", "card-text");
            cardBody.append(cardTitle, cardText);
            listContainer.append(cardDiv);

            const shortlisted = shortlist.some(elt => elt.centerId === element.recordid);
            console.log(shortlist)
            if (isAuth === 'true') {
                cardTitle.innerHTML = `
                    ${element.fields.cct_denomination} 
                    ${shortlisted ? '<a href="#" class="bi bi-star-fill text-primary add-to-shortlist" data-center-id="' + element.recordid + '"></a>' : '<a href="#" class="bi bi-star text-primary add-to-shortlist" data-center-id="' + element.recordid + '"></a>'}`;
            } else {
                cardTitle.innerHTML = `
                    ${element.fields.cct_denomination} 
                    <i class="bi bi-star text-dark"></i>`;
            }
            cardText.innerHTML = `
                <p>
                    ${element.fields.cct_adresse}
                    <br>
                    <i class="bi bi-telephone"></i> : ${element.fields.cct_tel}
                    <div class="row">
                        <div class="col"><p>Prix visite</p><h6>${element.fields.prix_visite} €</h6></div>
                        <div class="col"><p>Prix min contre visite</p><h6>${element.fields.prix_contre_visite_min} €</h6></div>
                        <div class="col"><p>Prix max contre visite</p><h6>${element.fields.prix_contre_visite_max} €</h6></div>
                    </div>
                </p>`

            cardTitle.firstElementChild.addEventListener("click", async (event) => {
                event.preventDefault();
                const saveUrl = document.getElementById("save-target").dataset.target;
                const centerId = event.target.dataset.centerId

                if (event.target.className.indexOf("bi-star ") > 0) {

                    event.target.className = event.target.className.replace("bi-star ", "bi-star-fill ")
                } else {
                    event.target.className = event.target.className.replace("bi-star-fill ", "bi-star ")
                }

                const data = await axios.post(saveUrl, { center: centerId }).then(function (response) {
                    console.log(response)
                    return response.data
                })
            })
        });
        // criteria.value = null
    })
})

