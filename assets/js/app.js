document.addEventListener("DOMContentLoaded", function () {
    const userLinks = document.querySelectorAll(".users__table--user");
    const detailsContainer = document.querySelector(".users__table--details");

    userLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            // Display a loading message while fetching user details
            detailsContainer.innerHTML = `<svg width="40" height="40" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_aj0A{transform-origin:center;animation:spinner_KYSC .75s infinite linear}@keyframes spinner_KYSC{100%{transform:rotate(360deg)}}</style><path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z" class="spinner_aj0A"/></svg>`;

            const userId = this.getAttribute("data-user-id");

            fetch(`https://jsonplaceholder.typicode.com/users/${userId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    // Update the DOM with user details
                    detailsContainer.innerHTML = `
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Email:</strong> ${data.email}</p>`;
                })
                .catch((error) => {
                    console.error("Error fetching user details:", error);
                    // Display an error message to the user
                    detailsContainer.innerHTML = `<p>Error loading user details.</p>`;
                });
        });
    });
});
