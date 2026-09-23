const searchInput = document.querySelector('#order-search');

const orderItems = document.querySelectorAll('.order-item');

searchInput.addEventListener('input', function () {

    const searchText = searchInput.value.toLowerCase();

    orderItems.forEach(function (item) {

        const customerName = item.dataset.customerName.toLowerCase();

        if (customerName.includes(searchText)) {
            item.hidden = false;
        } else {
            item.hidden = true;
        }

    });

});