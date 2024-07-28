document.addEventListener('DOMContentLoaded', (e) => {

   e.preventDefault();

   const registerParams = {
       form: document.getElementById('form_register')
   };

   function registerErrorReset(){
       document.getElementById('err_username_register').textContent = '';
       document.getElementById('err_firstname_register').textContent = '';
       document.getElementById('err_lastname_register').textContent = '';
       document.getElementById('err_email_register').textContent = '';
       document.getElementById('err_password_register').textContent = '';
       document.getElementById('err_re-password_register').textContent = '';
   }
   function errorsLog(code){
       switch (code){
           case 1:
           case 2:
               return "err_username_register";
           case 3:
               return "err_firstname_register";
           case 4:
               return "err_lastname_register";
           case 5:
           case 6:
               return "err_email_register";
           case 7:
               return "err_password_register";
           case 8:
               return "err_re-password_register";
       }
   }

    registerParams.form.onsubmit = async (e) => {
       e.preventDefault();

       const formData = new FormData(registerParams.form);

       const response = await data(formData).then(response => response.json());
       registerErrorReset();

       if(response.errors?.length >= 1){
           for (const error of response.errors) {
               const className = errorsLog(error.code);
               document.getElementById(className).textContent = error.message;
           }
       }
       else{
           registerParams.form.reset();
           registerErrorReset();

           window.location.href = response.url;
       }
   }

   async function data(data){
       const url = "https://funrniturefactory.userbliss.org/auth/register/validate.php";
       return await fetch(url, {
          method: 'POST',
          body: data
       });
   }
});

