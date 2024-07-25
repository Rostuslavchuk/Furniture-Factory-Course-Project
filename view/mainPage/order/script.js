document.addEventListener('DOMContentLoaded', (e) => {
    e.preventDefault();


    document.getElementById('back_btn').onclick = (e) => {
        e.preventDefault();
        window.location.href = '../menu/index.php';
    }


    const searchParams = {
        form: document.getElementById('form_search')
    }
    const createParams = {
        form: document.getElementById('form_create')
    }
    const success_alert = {
        success: document.getElementById('alert_success'),
        message_success: document.getElementById('success_message')
    }


    document.getElementById('search_button').onclick = (e) => {
        setTimeout(() => {
            errorSearchFormReset();
            searchParams.form.reset();
            if(!document.getElementById('table').classList.contains('none')){
                document.getElementById('table').classList.add('none');
            }
        }, 500);
    }
    document.getElementById('create_button').onclick = (e) => {
        setTimeout(() => {
            errorCreateFormReset();
            createParams.form.reset();
            if(!document.getElementById('table').classList.contains('none')){
                document.getElementById('table').classList.add('none');
            }
        }, 500);
    }


    // createFormErrors
    function errorCreateFormReset(){
        document.getElementById('err_create_username').textContent = '';
        document.getElementById('err_create_order_name').textContent = '';
        document.getElementById('err_create_order_description').textContent = '';
        document.getElementById('err_create_perform_to').textContent = '';
    }
    function createFormLog(code){
        switch (code){
            case 1:
            case 2:
                return 'err_create_username';
            case 3:
                return 'err_create_order_name';
            case 4:
                return 'err_create_order_description';
            case 5:
                return 'err_create_perform_to';
        }
    }
    // searchFormErrors
    function errorSearchFormReset(){
        document.getElementById('err_search_username').textContent = '';
    }
    function searchFormLog(code){
        switch (code){
            case 1:
            case 2:
                return 'err_search_username';
        }
    }


    // search
    searchParams.form.onsubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData(searchParams.form);
        formData.append('action', 'search_order');

        const response = await data(formData).then(response => response.json());
        errorSearchFormReset();

        if(response.errors?.length >= 1){
            for (const error of response.errors) {
                const className = searchFormLog(error.code);
                document.getElementById(className).textContent = error.message;
            }
        }
        else{
            errorSearchFormReset();
            searchParams.form.reset();

            if(response.data.empty_message){
                renderMessage(response.data.empty_message);
            }
            else{
                renderTable(response.data);
            }
        }
    }
    function renderTable(data){
        if(data){
            document.getElementById('body_search').innerHTML = '';
            for (const row of data) {
                const tableOrders = `
                <tr data-id="${row.id}">
                    <td class="order_username">${row.username}</td>
                    <td class="order_name">${row.order_name}</td>
                    <td class="order_description">${row.order_description}</td>
                    <td class="perform_to">${row.perform_to}</td>
                    <td>
                       <div class="d-flex align-items-center justify-content-center flex-row">
                           <button data-delete="${row.id}" type="button" class="border border-end-0 rounded rounded-end-0 delete-btn">
                               <i class='fa fa-trash' style="pointer-events: none;"></i>
                           </button>
                           <button data-update="${row.id}" type="button" class="border rounded rounded-start-0 update-btn">
                               <i class="fa fa-pen"></i>
                           </button>
                       </div>
                    </td>
                </tr>
            `;
                document.getElementById('body_search').insertAdjacentHTML('beforeend', tableOrders);
                const btnUpdate = document.querySelector(`#body_search tr[data-id="${parseInt(row.id)}"] button[data-update="${parseInt(row.id)}"]`);
                updateBindContext(btnUpdate);
                const btnDelete = document.querySelector(`#body_search tr[data-id="${parseInt(row.id)}"] button[data-delete="${parseInt(row.id)}"]`);
                deleteBindContext(btnDelete);
            }
            document.getElementById('search_table_empty')?.remove();
            document.getElementById('table').classList.remove('none');


        }
    }
    function renderMessage(message){
        const messageTable = `
            <tr id="search_table_empty">
                <td colspan="5" class="text-center text-title">${message}</td>
            </tr>
        `;
        if(!document.getElementById('body_search').getElementById('search_table_empty')) {
            document.getElementById('body_search').insertAdjacentHTML('beforeend', messageTable);
        }
        document.getElementById('table').classList.remove('none');
    }


    // create
    createParams.form.onsubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData(createParams.form);
        formData.append('action', 'create_order');

        const response = await data(formData).then(response => response.json());
        errorCreateFormReset();

        if(response.errors?.length >= 1){
            for (const error of response.errors){
                const className = createFormLog(error.code);
                document.getElementById(className).textContent = error.message;
            }
        }
        else{
            createParams.form.reset();
            errorCreateFormReset();

            success_alert.message_success.textContent = 'You successfully add order!';
            success_alert.success.style.setProperty('transform', 'translateY(0%)');
            setTimeout(() => {
                success_alert.success.style.setProperty('transform', 'translateY(150%)');
            }, 2000);
        }
    }


    // actions update, delete

    const updateModal = new bootstrap.Modal(document.getElementById('update_modal_order'));
    const deleteModal = new bootstrap.Modal(document.getElementById('delete_modal_order'));

    const updateParams = {
        form: document.getElementById('form_update_order'),
        footerClose: document.getElementById('close-update-footer'),
        headerClose: document.getElementById('close-update-header'),
        id_update: null
    }
    const deleteParams = {
        form: document.getElementById('form_delete_order'),
        text: document.getElementById('delete_text'),
        footerClose: document.getElementById('delete-close-footer'),
        headerClose: document.getElementById('delete-close-header'),
        id_delete: null
    }
    const defaultParams = {
        updateButtons: document.querySelectorAll('tr .update-btn'),
        deleteButtons: document.querySelectorAll('tr .delete-btn')
    }


    // update
    updateModal._element.onclick = (e) => {
        if(!e.target.closest('#form_update_order')){
            updateErrorsClear();
            updateParams.form.reset();
        }
    }
    updateParams.footerClose.onclick = (e) => {
        updateErrorsClear();
        updateParams.form.reset();
    }
    updateParams.headerClose.onclick = (e) => {
        updateErrorsClear();
        updateParams.form.reset();
    }

    function updateErrorsClear(){
        document.getElementById('err_update_order_name').textContent = '';
        document.getElementById('err_update_order_description').textContent = '';
        document.getElementById('err_update_perform_to').textContent = '';
    }
    function errUpdateLog(code){
        switch(code){
            case 1:
                return 'err_update_order_name';
            case 2:
                return 'err_update_order_description';
            case 3:
                return 'err_update_perform_to';
        }
    }

    defaultParams.updateButtons.forEach(btn => {
        updateBindContext(btn);
    });
    function updateBindContext(btn){
        if(btn){
            btn.onclick = (e) => {
                e.preventDefault();

                const getId = parseInt(btn.getAttribute("data-update"));
                const trRow = document.querySelector(`#body_search tr[data-id="${getId}"]`);

                if(trRow){
                    updateParams.id_update = getId;
                    updateParams.form.elements['update_order_name'].value = trRow.querySelector('.order_name').textContent;
                    updateParams.form.elements['update_order_description'].value = trRow.querySelector('.order_description').textContent;
                    updateParams.form.elements['update_perform_to'].value = trRow.querySelector('.perform_to').textContent;

                    updateModal.show();
                }
            }
        }
    }
    updateParams.form.onsubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData(updateParams.form);
        formData.append('action', 'update_order');
        formData.append('id', updateParams.id_update);

        const response = await data(formData).then(response => response.json());
        updateErrorsClear();

        if(response.errors?.length >= 1){
            for (const error of response.errors) {
                const className = errUpdateLog(error.code);
                document.getElementById(className).textContent = error.message;
            }
        }
        else{
            if(response.data){
                updateHtml(response.data);

                updateParams.footerClose.click();
                updateParams.form.reset();
                updateErrorsClear();

                success_alert.success.style.setProperty('transform', 'translateY(0%)');
                success_alert.message_success.textContent = 'You are successfully updated order!';
                setTimeout(() => {
                    success_alert.success.style.setProperty('transform', 'translateY(150%)');
                }, 2000);
            }
        }
    }
    function updateHtml(data){
        if(data){
            const id = parseInt(data.id);

            const trRow = document.querySelector(`#body_search tr[data-id="${id}"]`);
            if(trRow){
                trRow.querySelector('.order_name').textContent = data.order_name;
                trRow.querySelector('.order_description').textContent = data.order_description;
                trRow.querySelector('.perform_to').textContent = data.perform_to;
            }
        }
    }


    // delete order
    defaultParams.deleteButtons.forEach(btn => {
        deleteBindContext(btn);
    });
    function deleteBindContext(btn){
        if(btn){
            btn.onclick = (e) => {
                e.preventDefault();

                const getId = parseInt(e.target.getAttribute('data-delete'));
                const trRow = document.querySelector(`#body_search tr[data-id="${getId}"]`);

                if(trRow){
                    deleteParams.id_delete = getId;
                    deleteParams.text.textContent = trRow.querySelector('.order_name').textContent;
                    deleteModal.show();
                }
            }
        }
    }
    deleteParams.form.onsubmit = async (e) => {
        e.preventDefault();
        const dataObj = {id: deleteParams.id_delete, action: 'delete'};

        const response = await data(Object.entries(dataObj)).then(response => response.json());
        if(response.status){
            document.querySelector(`#body_search tr[data-id="${deleteParams.id_delete}"]`).remove();

            deleteParams.footerClose.click();
            deleteParams.form.reset();

            success_alert.success.style.setProperty('transform', 'translateY(0%)');
            success_alert.message_success.textContent = 'You are successfully deleted order!';
            setTimeout(() => {
                success_alert.success.style.setProperty('transform', 'translateY(150%)');
            }, 2000);
        }
    }


    async function data(data){
        const url = "http://localhost:63342/furniture_factory/view/mainPage/order/validate.php";
        return await fetch(url, {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(data)),
            headers: {
                'Content-Type': 'application/json'
            }
        });
    }
});
