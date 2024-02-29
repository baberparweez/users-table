document.addEventListener("DOMContentLoaded", function () {
    const userLinks = document.querySelectorAll(".user-detail");

    userLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const userId = this.getAttribute("data-user-id");

            fetch(`https://jsonplaceholder.typicode.com/users/${userId}`)
                .then((response) => response.json())
                .then((data) => {
                    const detailsContainer =
                        document.getElementById("user-details");
                    detailsContainer.innerHTML = `<p>Name: ${data.name}</p><p>Email: ${data.email}</p>`;
                })
                .catch((error) =>
                    console.error("Error fetching user details:", error)
                );
        });
    });
});
