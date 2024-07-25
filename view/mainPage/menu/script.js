document.addEventListener('DOMContentLoaded', (e) => {
    e.preventDefault();

    const modalConfirmQuit = new bootstrap.Modal(document.getElementById('confirm_quit'));

    const modalParams = {
        form: document.getElementById('form_quit')
    }

    document.getElementById('quit_btn').onclick = (e) => {
        e.preventDefault();
        modalConfirmQuit.show();
    }
    document.getElementById('work_staff_btn').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../workStaff/main/index.php';
    }
    document.getElementById('order_link').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../order/index.php';
    }
    modalParams.form.onsubmit = (e) => {
        e.preventDefault();
        window.location.href = '../../../auth/login/login.php';
    }

})