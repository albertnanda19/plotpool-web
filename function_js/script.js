function seeMore(){
    window.location.href = "../visit-novel/index.php";
};

function toAboutus(){
    window.location.href = "../about-developers-page/index.php";
}

function toHome(){
    window.location.href = "../home/index.php";
}

function toTitles(){
    window.location.href = "../titles-page/index.php";
}

document.getElementById("log-out").addEventListener("click", logout);

function logout() {
    swal({
        title: "Are you sure you want to logout?",
        text: "You will be redirected to the login page",
        buttons: true,
        dangerMode: true,
    })
    .then((willLogout) => {
        if (willLogout) {
            fetch('../function_php/logout.php', {
                method: 'POST'
            })
            .then(response => {
                window.location.href = "../index.html";
            })
            .catch(error => {
                console.log(error);
                swal("Error", "Failed to logout", "error");
            });
        } else {
            swal("Logout cancelled!");
        }
    });
}

function direct()
{
    window.location.href = '../edit-profile/index.php';
}

const searchForm = document.getElementById('searchForm');
const searchInput = searchForm.querySelector('input[type="text"]');

searchForm.addEventListener('submit', function(event) {
    event.preventDefault();
    const keyword = searchInput.value.trim();
    if (keyword !== '') {
        window.location.href = `../titles-page/index.php?search=${encodeURIComponent(keyword)}`;
    }
});