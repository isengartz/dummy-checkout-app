import {HTTP_OK} from "../util/consts";

const renderOrderTable = async () => {
    const $wrapper = window.$('#product-table-wrapper');
    const response = await window.axios.post('/render-order-table', {});
    if (response.status === HTTP_OK) {
        $($wrapper).html(response.data.data.view);
    } else {
        console.error(response.data.error);
        alert('An error occured check your console');
    }
}


window.$(document).on('change', '.shipping_on_change_handler', async function () {
    const $val = window.$(this).val();
    const response = await window.axios.post('/update-shipping', {'shipping_option': $val});
    if (response.status === HTTP_OK) {
        await renderOrderTable();
    } else {
        console.error(response.data.error);
        alert('An error occured check your console');
    }
});

