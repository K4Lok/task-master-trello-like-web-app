function authentication() {
    const sessionId = Cookies.get('PHPSESSID');
    const uemail = Cookies.get('uemail');

    const formData = new FormData();
    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    if (!sessionId || !uemail) {
        location.href = './login.html';
    }

    fetch(`${API_URI}/api/auth`, {
        method: 'POST',
        body: formData,
    })
        .then(res => {
            if (res.ok) {
                return res.json();
            }
        })
        .then(response => {
            if (!response.succeed) {
                console.log('Authen not good!', response);
                location.href = './login.html';
            }
        })
}

authentication();