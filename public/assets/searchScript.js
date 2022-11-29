

window.addEventListener("DOMContentLoaded", (event) => {
    //the event occurred.
    const btn = document.getElementById("search-button");
    const url = document.getElementById("search-button").href;
    const criteria = document.getElementById("search-criteria");
    const mapDiv = document.getElementById("map");
    const mapViewToggler = document.getElementById("map-view")
    const listViewToggler = document.getElementById("list-view")
    const listContainer = document.getElementById("result-list-container");
    const resultHeader = document.getElementById("result-header");
    const resultHeaderText = document.getElementById("result-header-text");
    const jsUser = document.getElementById("js-user");
    const isAuth = jsUser.dataset.isAuthenticated;
    const noResutBlock = document.getElementById("no-result");

    const map = L.map('map');
    var markers = L.layerGroup();
    map.addLayer(markers);

    async function getCentersList(event) {
        event.preventDefault();
        let coordinatesApiUrl = "https://geo.api.gouv.fr/communes?fields=centre&format=json&geometry=centre"

        if (isNaN(criteria.value)) {
            coordinatesApiUrl += "&nom=" + criteria.value
        } else {
            coordinatesApiUrl += "&codePostal=" + criteria.value
        }

        listContainer.innerHTML = ""
        if (!event.defaultPrevented) { return false }

        const response = await axios.post(url, { location: criteria.value }).then(function (response) {
            return JSON.parse(response.data)
        })

        const { data, shortlist } = response;

        if (data.nhits > 0) {
            resultHeaderText.innerText = `${data.nhits} centres trouvés...`;
            resultHeader.className = resultHeader.className.replace("d-none", "")
            if (noResutBlock.className.indexOf("d-none") < 0) {
                noResutBlock.className = noResutBlock.className + " d-none";
            }
        } else {
            // resultHeaderText.innerText = "Aucun résultat pour votre recherche...";
            if (!resultHeader.className.indexOf("d-none") > 0) {
                resultHeader.className = resultHeader.className + "d-none";
            }
            noResutBlock.className = noResutBlock.className.replace("d-none", "");
        }

        const mapCoordinates = await axios.get(coordinatesApiUrl).then((response) => { console.log(response); return [response.data[0].centre.coordinates[1], response.data[0].centre.coordinates[0]] })

        map.invalidateSize();
        markers.clearLayers();
        map.setView(mapCoordinates, 11);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);



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
                    <i class="bi bi-telephone"></i> : ${element.fields.cct_tel ? element.fields.cct_tel : "non renseigné"}
                    <div class="row">
                        <div class="col"><p>Prix visite</p><h6>${element.fields.prix_visite} €</h6></div>
                        <div class="col"><p>Prix min contre visite</p><h6>${element.fields.prix_contre_visite_min} €</h6></div>
                        <div class="col"><p>Prix max contre visite</p><h6>${element.fields.prix_contre_visite_max} €</h6></div>
                    </div>
                </p>`

            async function shortListHandler(event) {
                event.preventDefault();
                const saveUrl = document.getElementById("save-target").dataset.target;
                const centerId = event.target.dataset.centerId

                if (event.target.className.indexOf("bi-star ") > 0) {

                    event.target.className = event.target.className.replace("bi-star ", "bi-star-fill ")
                } else {
                    event.target.className = event.target.className.replace("bi-star-fill ", "bi-star ")
                }

                const data = await axios.post(saveUrl, { center: centerId }).then(function (response) {
                    return response.data
                })
            }

            cardTitle.firstElementChild.addEventListener("click", shortListHandler)


            const marker = L.marker(element.fields.latitude);
            marker.bindPopup(
                `<h6>${element.fields.cct_denomination}</h6>
                <p>
                    ${element.fields.cct_adresse}
                    <br>
                    <i class="bi bi-telephone"></i> : ${element.fields.cct_tel ? element.fields.cct_tel : "non renseigné"}
                    <br>
                    Prix visite : ${element.fields.prix_visite} €
                    <br>
                    Prix min contre visite : ${element.fields.prix_contre_visite_min} €
                    <br>
                    Prix max contre visite : ${element.fields.prix_contre_visite_max} €
                </p>`
            )
            markers.addLayer(marker)

        });
    }


    btn.addEventListener("click", getCentersList)


    function changeView() {
        if (mapDiv.className.indexOf("d-none") > 0) {

            mapDiv.className = mapDiv.className.replace("d-none", "")
            map.invalidateSize();
            listContainer.className = listContainer.className + " d-none"
        } else {
            mapDiv.className = mapDiv.className + " d-none"
            listContainer.className = listContainer.className.replace("d-none", "")
        }
    }
    mapViewToggler.addEventListener("click", changeView)

    listViewToggler.addEventListener("click", changeView)
})