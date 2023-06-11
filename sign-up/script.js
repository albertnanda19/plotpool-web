let users = []

function signUp()
{
    const email = document.getElementById("input-email").value;
    const username = document.getElementById("input-username").value;
    const password = document.getElementById("input-password").value;

    //mengecek apakah email dan username dan password diisi
    if(email === '' && username === '' && password === '')
    {
        event.preventDefault();
        swal({
            title: "Email, username, dan password wajib diisi!",
            text: "Click anywhere to continue",
            buttons: false,
            dangerMode: true,
            icon: "error"
            });
        return false;
    }else if(email === '')
    {
        event.preventDefault();
        swal({
            title: "Email wajib diisi!",
            text: "Click anywhere to continue",
            buttons: false,
            dangerMode: true,
            icon: "error"
            });
        return false;
    }else if(username === '')
    {
        event.preventDefault();
        swal({
            title: "Username wajib diisi!",
            text: "Click anywhere to continue",
            buttons: false,
            dangerMode: true,
            icon: "error"
            });
        return false;
    }else if(password == '')
    {
        event.preventDefault();
        swal({
            title: "Password wajib diisi!",
            text: "Click anywhere to continue",
            buttons: false,
            dangerMode: true,
            icon: "error"
            });
        return false;
    }

    //mengecek username apakah sudah ada atau belum
    for(let i = 0; i < users.length; i++)
    {
        event.preventDefault();
        if(users[i].username === username)
        {
            swal({
                title: "Username sudah terdaftar!",
                text: "Click anywhere to continue",
                buttons: false,
                dangerMode: true,
                icon: "error"
                });
            return false;
        }
    }


    //menambahkan username dan password dan email ke dalam array
    users.push({username: username, password: password});
    event.preventDefault();
    swal({
        title: "Sign up telah berhasil!",
        text: "Click anywhere to continue",
        buttons: false,
        dangerMode: true,
        icon: "info"
        });

    // mereset input email, username dan password
    document.getElementById("input-email").value = '';
    document.getElementById("input-username").value = '';
    document.getElementById("input-password").value = '';

    return true;
    
}



