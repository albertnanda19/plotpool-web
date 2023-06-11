function signIn()
{
    let username = document.getElementById('input-username').value;
    let password = document.getElementById('input-password').value;

    if(username == "123" && password == '123')
    {
        window.location.href = '../home/index.html';
    }else{
        swal({
            title: "Incorrect username/password",
            text: "Click anywhere to continue",
            buttons: false,
            dangerMode: true,
            icon: "warning"
            })
        return false;
    }
}