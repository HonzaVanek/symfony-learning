const loadButton = document.querySelector('#load-api-orders');
const orderList = document.querySelector('#api-order-list');
const status = document.querySelector('#api-status');

loadButton.addEventListener('click', async function () {
    status.textContent = 'Načítám...';
    loadButton.disabled = true;

    try {
        const response = await fetch('/api/orders');

        if (!response.ok) {
            throw new Error(`HTTP chyba: ${response.status}`);
        }

        const orders = await response.json();

        orderList.replaceChildren();

        orders.forEach(function (order) {
            const item = document.createElement('li');

            item.textContent =
                `#${order.id} – ${order.customer.name} – ${order.total} Kč`;

            orderList.appendChild(item);
        });

        status.textContent = `Načteno objednávek: ${orders.length}`;
    } catch (error) {
        console.error(error);

        status.textContent = 'Objednávky se nepodařilo načíst.';
    } finally {
        loadButton.disabled = false;
    }
});