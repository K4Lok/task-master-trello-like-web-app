function authentication() {
    const sessionId = Cookies.get('PHPSESSID');
    const uemail = Cookies.get('uemail');

    if (!sessionId || !uemail) {
        location.href = './login.html';
    }

    fetch(`http://localhost:5050/auth?token=${sessionId}&uemail=${uemail}`, {
        method: 'GET',
    })
        .then(res => {
            if (res.ok) {
                return res.json();
            }
        })
        .then(response => {
            if (response.succeed) {
                console.log('OK!', response);
            }
            else {
                console.log('NOT OK!', response);
                location.href = './login.html';
            }
        })
}

authentication();