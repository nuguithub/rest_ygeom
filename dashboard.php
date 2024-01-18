<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Events Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <!-- navbar -->
    <nav class="navbar bg-dark border-bottom border-body">
        <div class="container my-3">
            <a class="navbar-brand text-light fw-bold fs-2">YGEOM-</a>
            <a class="btn btn-warning fw-bold px-3" href="#" role="button">LOGIN</a>
        </div>
    </nav>

    <div class="container mt-3 text-center">
        <h1 class="fw-bold">Events Dashboard</h1>
    </div>

    <section id="carousel">
        <div class="container">
            <div id="carouselExample" class="carousel slide">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://wallpapercave.com/wp/wp6058956.jpg" class="d-block w-100 object-fit-cover"
                            alt="..." style="height: 50vh;">
                    </div>
                    <div class="carousel-item">
                        <img src="https://free4kwallpapers.com/uploads/originals/2020/10/13/digital-landscape-wallpaper.jpg"
                            class="d-block w-100 object-fit-cover" alt="..." style="height: 50vh;">
                    </div>
                    <div class="carousel-item">
                        <img src="https://cdn.wallpapersafari.com/55/83/Pl6QHc.jpg"
                            class="d-block w-100 object-fit-cover" alt="..." style="height: 50vh;">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>


    <section id="main">
        <div class="container my-3">

            <div class="col-10 mx-auto" id="events-list">
            </div>

        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
    function fetchEvents() {
        axios
            .get("/ygeom/events") // Adjust the URL based on your API structure
            .then((response) => {
                const eventsList = document.getElementById("events-list");
                eventsList.innerHTML = "";

                if (response.data.length > 0) {
                    var row; // Declare row outside the loop

                    response.data.forEach((event, index) => {
                        // Create a new row for the first card or every three cards
                        if (index % 3 === 0) {
                            row = document.createElement("div");
                            row.className = "row";
                            eventsList.appendChild(row);
                        }


                        // Create the card
                        const card = document.createElement("div");
                        card.className =
                            "card col-lg-4 mx-auto"; // Each card takes 4 columns on medium-sized screens
                        card.style = "margin-bottom: 20px;";

                        const cardBody = document.createElement("div");
                        cardBody.className = "card-body";

                        const eventName = document.createElement("h5");
                        eventName.className = "card-title";
                        eventName.textContent = event.event_name;

                        const eventDescription = document.createElement("h6");
                        eventDescription.className = "card-subtitle mb-2 text-body-secondary";
                        eventDescription.textContent = event.event_description;

                        const eventDate = document.createElement("p");
                        eventDate.className = "card-text";

                        // Assuming event.event_date is a string in the format "2023-12-22 02:00:00"
                        const rawDate = new Date(event.event_date);

                        // Format the date
                        const formattedDate = new Intl.DateTimeFormat('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                            hour12: true
                        }).format(rawDate);

                        eventDate.textContent = `Date: ${formattedDate}`;


                        const eventManager = document.createElement("p");
                        eventManager.className = "card-text";
                        eventManager.textContent = `Event Manager: ${event.event_manager_id}`;

                        cardBody.appendChild(eventName);
                        cardBody.appendChild(eventDescription);
                        cardBody.appendChild(eventDate);
                        cardBody.appendChild(eventManager);

                        card.appendChild(cardBody);
                        row.appendChild(card);
                    });
                } else {
                    const noEventsMessage = document.createElement("p");
                    noEventsMessage.textContent = "No events available.";
                    eventsList.appendChild(noEventsMessage);
                }
            })
            .catch((error) => {
                console.error("Error fetching events:", error);
            });
    }

    fetchEvents();
    </script>
</body>

</html>