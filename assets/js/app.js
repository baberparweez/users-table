document.addEventListener("DOMContentLoaded", function () {
    const userLinks = document.querySelectorAll(".users__table--user");

    userLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const userId = this.getAttribute("data-user-id");

            // Asynchronous call to fetch user details
            fetch(`https://jsonplaceholder.typicode.com/users/${userId}`)
                .then((response) => response.json()) // Asynchronously parse the JSON response
                .then((data) => {
                    // Asynchronously update the DOM with the fetched data
                    const detailsContainer = document.querySelector(
                        ".users__table--details"
                    );
                    detailsContainer.innerHTML = `<p>Name: ${data.name}</p><p>Email: ${data.email}</p>`;
                })
                .catch((error) =>
                    console.error("Error fetching user details:", error)
                ); // Handle any errors that occur during the fetch or data processing
        });
    });
});
