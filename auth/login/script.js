document.addEventListener('DOMContentLoaded', (e) => {

    e.preventDefault();

    const loginParams = {
        form: document.getElementById('form_login')
    };

    function loginErrorReset(){
        document.getElementById('err_username_login').textContent = '';
        document.getElementById('err_password_login').textContent = '';
    }
    function errorsLog(code){
        switch (code){
            case 1:
            case 2:
                return "err_username_login";
            case 3:
            case 4:
                return "err_password_login";
        }
    }

    loginParams.form.onsubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData(loginParams.form);

        const response = await data(formData).then(response => response.json());
        loginErrorReset();

        if(response.errors?.length >= 1){
            for (const error of response.errors) {
                const className = errorsLog(error.code);
                document.getElementById(className).textContent = error.message;
            }
        }
        else{
            loginParams.form.reset();
            loginErrorReset();
            window.location.href = response.url;
        }
    }

    async function data(data){
        const url = "https://funrniturefactory.userbliss.org/auth/login/validate.php";
        return await fetch(url, {
            method: 'POST',
            body: data
        });
    }
});

