document.addEventListener('DOMContentLoaded', (e) => {

    e.preventDefault();

    const searchParams = {
        searchInput: document.getElementById('search_inp'),
        searchBtn: document.getElementById('search_btn')
    }

    document.getElementById('back_btn').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../main/index.php';
    }

    searchParams.searchInput.oninput = (e) => {
        e.preventDefault();

        const value = e.target.value;

        if (value.length > 0 && value.match(/^[a-zA-Z]+$/)) {
            searchParams.searchBtn.disabled = false;
        } else {
            searchParams.searchBtn.disabled = true;
            document.querySelectorAll('#table_body tr').forEach(el => {
                el.classList.remove('none');
            });
            document.getElementById('empty')?.remove();
            
            setEmpty(document.querySelectorAll("#table_body tr"));
        }
    }

    searchParams.searchBtn.onclick = async (e) => {
        e.preventDefault();

        const val = searchParams.searchInput.value;

        const formData = new FormData();
        formData.append('value', val);
        formData.append('action', 'search');

        const response = await data(formData).then(response => response.json());

        if (response.array && response.status) {
            checkMatch(response.array);
        }
    }

    function checkMatch(array) {
        const bodyTable = document.getElementById('table_body');
        const rows = bodyTable.querySelectorAll('tr');

        rows.forEach(row => {
            const id = parseInt(row.getAttribute('data-id'));

            if (array.some(el => parseInt(el.id) === id)) {
                row.classList.remove('none');
            } else {
                row.classList.add('none');
            }
        });
        checkLength(array);
    }

    function checkLength(array) {
        const body = document.getElementById('table_body');
        
        if (array.length === 0) {
            if(document.getElementById('empty')){
                document.getElementById('empty').remove();
            }
            
            body.insertAdjacentHTML("beforeend", `
                <tr id="empty">
                    <td colspan="10" class="text-title text-center">Not Found!</td>
                </tr>
            `);
   
        } 
        else {
            document.getElementById("empty")?.remove();
        }
    }

    // added functionality
    const addUpdateModal = new bootstrap.Modal(document.getElementById('add_update_modal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('delete_modal'));

    const bodyTable = document.getElementById('table_body');

    setEmpty(document.querySelectorAll("#table_body tr"));

    function setEmpty(bodyTable) {
        const body = document.getElementById('table_body');
        if (bodyTable.length === 0) {
            body.insertAdjacentHTML("beforeend", `
                <tr id="empty" >
                    <td colspan="10" class="text-title text-center">Table is Empty!</td>
                </tr>
            `);
        } else {
            document.getElementById("empty")?.remove();
        }
    }

    const addUpdateModalParams = {
        modalTitle: document.getElementById('title_modal'),
        modalForm: document.getElementById('add_update_form'),
        submitForm: document.getElementById('submit_button'),
        form: document.getElementById('add_update_form'),
        closeBtnTriggerFooter: document.getElementById('close-modal-footer'),
        closeBtnTriggerHeader: document.getElementById('close-modal-header'),
        action: null
    };

    const deleteParams = {
        form: document.getElementById('delete_form'),
        closeBtnTriggerFooter: document.getElementById('close-delete-modal-footer'),
        textName: document.getElementById('delete_name')
    }

    const alert = {
        success_message: document.getElementById('success_message'),
        alert_success: document.getElementById('alert_success')
    }

    const alertAccess = {
        warning_update: document.getElementById('alert_warning_update'),
        warning_add: document.getElementById('alert_warning_add'),
        warning_delete: document.getElementById('alert_warning_delete')
    }

    const paramsDefault = {
        addButton: document.getElementById('add'),
        updateButtons: document.querySelectorAll('.update-btn'),
        deleteButtons: document.querySelectorAll('.delete-btn'),
    };


    // action on errors for update add

    function modalUpdateAddErrors() {
        document.getElementById('err_employee_name').textContent = '';
        document.getElementById('err_position').textContent = '';
        document.getElementById('err_system_managed').textContent = '';
        document.getElementById('err_issue_reported').textContent = '';
        document.getElementById('err_resolution').textContent = '';
        document.getElementById('err_date_reported').textContent = '';
        document.getElementById('err_date_resolved').textContent = '';
        document.getElementById('salary').textContent = '';
        document.getElementById('err_image_employee').textContent = '';
    }

    function errorLog(code) {
        switch (code) {
            case 1:
                return 'err_employee_name';
            case 2:
                return 'err_position';
            case 3:
                return 'err_system_managed'
            case 4:
                return 'err_issue_reported';
            case 5:
                return 'err_resolution';
            case 6:
                return 'err_date_reported';
            case 7:
                return 'err_date_resolved';
            case 8:
                return 'salary';
            case 9:
                return 'err_image_employee';
        }
    }

    // clear after close
    addUpdateModal._element.onclick = (e) => {
        if (!e.target.closest('#add_update_form')) {
            modalUpdateAddErrors();
            addUpdateModalParams.form.reset();
        }
    }
    addUpdateModalParams.closeBtnTriggerHeader.onclick = (e) => {
        modalUpdateAddErrors();
        addUpdateModalParams.form.reset();
    }
    addUpdateModalParams.closeBtnTriggerFooter.onclick = (e) => {
        modalUpdateAddErrors();
        addUpdateModalParams.form.reset();
    }


    // update add delete actions exit
    paramsDefault.addButton.onclick = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('action', 'access');

        const response = await data(formData).then(response => response.json());
        if(response.status){
            document.getElementById('update_id').value = '0';

            addUpdateModalParams.opened = true;

            addUpdateModalParams.modalTitle.textContent = 'Add';
            addUpdateModalParams.submitForm.textContent = 'Add';

            addUpdateModalParams.action = 'add';

            addUpdateModal.show();
        }
        else{
            alertAccess.warning_add.style.setProperty('transform', 'translateY(0%)');
            setTimeout(() => {
                alertAccess.warning_add.style.setProperty('transform', 'translateY(150%)');
            }, 2000);
        }
    }
    paramsDefault.updateButtons.forEach(btn => {
        buttonUpdate(btn);
    });
    paramsDefault.deleteButtons.forEach(btn => {
        buttonDelete(btn);
    });


    //add update submit
    addUpdateModalParams.form.onsubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData(addUpdateModalParams.form);
        formData.append('action', addUpdateModalParams.action);

        const imageFile = document.getElementById('image_employee');
        formData.append('image_employee', imageFile.files[0]);

        const response = await data(formData).then(response => response.json());

        modalUpdateAddErrors();

        if (response.errors?.length >= 1 && response.status === false) {
            for (const error of response.errors) {
                const className = errorLog(parseInt(error.code));
                document.getElementById(className).textContent = error.message;
            }
        } else {
            if (addUpdateModalParams.action === 'add') {
                alert.success_message.textContent = 'You are successfully added employee!';
                add(response.data);
                setEmpty(document.querySelectorAll("#table_body tr"));
                bindDataToUpdate(response.data.id);
                bindForDelete(response.data.id);
            } else {
                alert.success_message.textContent = 'You are successfully updated employee!';
                update(response.data);
            }
            addUpdateModalParams.closeBtnTriggerFooter.click();
            modalUpdateAddErrors();
            addUpdateModalParams.form.reset();

            alert.alert_success.style.setProperty('transform', 'translateY(0%)');
            setTimeout(() => {
                alert.alert_success.style.setProperty('transform', 'translateY(150%)');
            }, 2000);
        }
    }


    // delete submit
    deleteParams.form.onsubmit = async (e) => {
        e.preventDefault();

        const formDataDelete = new FormData(deleteParams.form);
        formDataDelete.append('action', 'delete');

        const response = await data(formDataDelete).then(response => response.json());

        if (response.status) {
            document.querySelector(`#table_body tr[data-id="${parseInt(response.delete_id)}"]`).remove();
            setEmpty(document.querySelectorAll("#table_body tr"));

            alert.success_message.textContent = 'You are successfully deleted employee!';

            alert.alert_success.style.setProperty('transform', 'translateY(0%)');
            setTimeout(() => {
                alert.alert_success.style.setProperty('transform', 'translateY(150%)');
            }, 2000);

            deleteParams.closeBtnTriggerFooter.click();
        }
    }

    // html add, update

    function add(data) {
        const newData = `
        <tr data-id="${data.id}">
            <td class="employee_name" >${data.employee_name}</td>
            <td class="position" >${data.position}</td>
            <td class="system_managed" >${data.system_managed}</td>
            <td class="issue_reported" >${data.issue_reported}</td>
            <td class="resolution" >${data.resolution}</td>
            <td class="date_reported" >${data.date_reported}</td>
            <td class="date_resolved" >${data.date_resolved}</td>
            <td class="salary" >${data.salary}</td>
            <td class="image_employee">
                <img src="./it/IT${data.id}.jpg" style="width: 100%; height: 100%; object-fit: cover;" alt="Emploee Image">
            </td>
            <td>
                <div class="d-flex align-items-center flex-row">
                    <button data-delete="${data.id}" type="button" class="border border-end-0 rounded rounded-end-0 delete-btn">
                        <i class='fa fa-trash'></i>
                    </button>
                    <button data-update="${data.id}" type="button" class="border rounded rounded-start-0 update-btn">
                        <i class="fa fa-pen"></i>
                    </button>
                </div>
            </td>
        </tr>
        `;
        bodyTable.insertAdjacentHTML('beforeend', newData);
    }

    function update(data) {
        const id_update = parseInt(data.id);
        const updateHtml = document.querySelector(`#table_body tr[data-id="${id_update}"]`);

        if (updateHtml) {
            updateHtml.querySelector('.employee_name').textContent = data.employee_name;
            updateHtml.querySelector('.position').textContent = data.position;
            updateHtml.querySelector('.system_managed').textContent = data.system_managed;
            updateHtml.querySelector('.issue_reported').textContent = data.issue_reported;
            updateHtml.querySelector('.resolution').textContent = data.resolution;
            updateHtml.querySelector('.date_reported').textContent = data.date_reported;
            updateHtml.querySelector('.date_resolved').textContent = data.date_resolved;
            updateHtml.querySelector('.salary').textContent = data.salary;

            const imgElement = document.querySelector(`img[data-img="${id_update}"]`);
            if (imgElement) {
                const timestamp = new Date().getTime();
                imgElement.src = `./it/IT${id_update}.jpg?timestamp=${timestamp}`;
            }
        }
    }


    // bind after add (update, delete)
    function bindDataToUpdate(id) {
        const btn = document.querySelector(`#table_body tr[data-id="${parseInt(id)}"] td button[data-update="${parseInt(id)}"]`);
        if (btn) {
            buttonUpdate(btn);
        }
    }

    function buttonUpdate(btn) {
        if (btn) {
            btn.onclick = async (e) => {
                e.preventDefault();

                const formData = new FormData();
                formData.append('action', 'access');

                const response = await data(formData).then(response => response.json());
                if(response.status) {
                    addUpdateModalParams.modalTitle.textContent = 'Update';
                    addUpdateModalParams.submitForm.textContent = 'Update';

                    addUpdateModalParams.action = 'update';

                    const getId = parseInt(btn.getAttribute('data-update'));
                    const getData = document.querySelector(`#table_body tr[data-id="${getId}"]`);

                    if (getData) {
                        addUpdateModalParams.form.elements['update_id'].value = getId;

                        addUpdateModalParams.form.elements['employee_name'].value = getData.querySelector('.employee_name').textContent;
                        addUpdateModalParams.form.elements['position'].value = getData.querySelector('.position').textContent;
                        addUpdateModalParams.form.elements['system_managed'].value = getData.querySelector('.system_managed').textContent;
                        addUpdateModalParams.form.elements['issue_reported'].value = getData.querySelector('.issue_reported').textContent;
                        addUpdateModalParams.form.elements['resolution'].value = getData.querySelector('.resolution').textContent;
                        addUpdateModalParams.form.elements['date_reported'].value = getData.querySelector('.date_reported').textContent;
                        addUpdateModalParams.form.elements['date_resolved'].value = getData.querySelector('.date_resolved').textContent;
                        addUpdateModalParams.form.elements['salary'].value = getData.querySelector('.salary').textContent;

                        addUpdateModal.show();
                    }
                }
                else{
                    alertAccess.warning_update.style.setProperty('transform', 'translateY(0%)');
                    setTimeout(() => {
                        alertAccess.warning_update.style.setProperty('transform', 'translateY(150%)');
                    }, 2000);
                }
            }
        }
    }

    // delete bind
    function bindForDelete(id) {
        const btn = document.querySelector(`#table_body tr[data-id="${id}"] td button[data-delete="${id}"]`);
        if (btn) {
            buttonDelete(btn);
        }
    }

    function buttonDelete(btn) {
        if (btn) {
            btn.onclick = async (e) => {
                e.preventDefault();

                const formData = new FormData();
                formData.append('action', 'access');

                const response = await data(formData).then(response => response.json());

                if(response.status) {
                    const getId = parseInt(btn.getAttribute('data-delete'));
                    const getData = document.querySelector(`#table_body tr[data-id="${getId}"]`);

                    if (getData) {
                        deleteParams.form.elements['delete_id'].value = getId;
                        document.getElementById('delete_name').textContent = getData.querySelector('.employee_name').textContent;
                        deleteModal.show();
                    }
                }
                else{
                    alertAccess.warning_delete.style.setProperty('transform', 'translateY(0%)');
                    setTimeout(() => {
                        alertAccess.warning_delete.style.setProperty('transform', 'translateY(150%)');
                    }, 2000);
                }
            }
        }
    }


    async function data(data) {
        const url = "https://funrniturefactory.userbliss.org/view/mainPage/workStaff/it/validate.php";

        return await fetch(url, {
            method: 'POST',
            body: data
        });
    }
});


