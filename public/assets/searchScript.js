

document.addEventListener("DOMContentLoaded", (event) => {
    //the event occurred.
    const btn = document.getElementById("search-button");
    const url = document.getElementById("search-button").href;
    const criteria = document.getElementById("search-criteria");

    btn.addEventListener("click", async (event) => {
        event.preventDefault();
        const listContainer = document.getElementById("result-list-container");
        const resultHeader = document.getElementById("result-header");
        const resultHeaderText = document.getElementById("result-header-text");

        listContainer.innerHTML = ""
        if (!event.defaultPrevented) { return false }

        console.log(url);
        const data = await axios.post(url, { location: criteria.value }).then(function (response) {
            console.log(response)
            return response.data.records
            // do whatever you want if console is [object object] then stringify the response
        })

        console.log(data)

        if (data.length > 0) {
            resultHeaderText.innerText = `${data.length} centres trouvés...`;
        } else {
            resultHeaderText.innerText = "Aucun résultat pour votre recherche...";
        }


        data.forEach(element => {
            const cardDiv = document.createElement("div");
            cardDiv.setAttribute("class", "col-sm-12 col-md-8 card border-secondary mb-3")
            const cardBody = document.createElement("div");
            cardBody.setAttribute("class", "card-body");
            cardDiv.appendChild(cardBody)
            const cardTitle = document.createElement("h4");
            cardTitle.setAttribute("class", "card-title");
            const cardText = document.createElement("p");
            cardText.setAttribute("class", "card-text");
            cardBody.append(cardTitle, cardText)
            listContainer.append(cardDiv)
            cardTitle.innerHTML = `${element.fields.cct_denomination} <i class="bi bi-star-fill text-primary"></i>`;
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
        });
        // criteria.value = null
    })
})

