const loadButton = document.querySelector('#load-api-orders');
const orderList = document.querySelector('#api-order-list');
const status = document.querySelector('#api-status');

function createOrderItem(order) {

    const item = document.createElement('li');

    const orderText = document.createElement('span');

    orderText.textContent =
        `#${order.id} – ${order.customer.name} – ${order.total} Kč `;

    const deleteButton = document.createElement('button');

    deleteButton.type = 'button';
    deleteButton.textContent = 'Smazat';

    deleteButton.addEventListener('click', async function () {

        const confirmed = confirm(
            `Opravdu chcete smazat objednávku #${order.id}?`
        );

        if (!confirmed) {
            return;
        }

        deleteButton.disabled = true;

        try {

            const response = await fetch(`/api/orders/${order.id}`, {
                method: 'DELETE',
            });

            if (!response.ok) {
                throw new Error(`HTTP chyba: ${response.status}`);
            }

            item.remove();

        } catch (error) {

            console.error(error);

            alert('Objednávku se nepodařilo smazat.');

            deleteButton.disabled = false;
        }

    });

    item.appendChild(orderText);
    item.appendChild(deleteButton);

    return item;
}

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
            const item = createOrderItem(order);

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


const apiForm = document.querySelector('#api-order-form');
const createStatus = document.querySelector('#api-create-status');

apiForm.addEventListener('submit', async function (event) {
    event.preventDefault();

    const customerId = Number(
        document.querySelector('#api-customer-id').value
    );

    const total = Number(
        document.querySelector('#api-total').value
    );

    const payload = {
        customerId: customerId,
        total: total,
    };

    try {
        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error ?? 'API request failed');
        }

        createStatus.textContent =
            `Vytvořena objednávka #${result.id}`;

        
        const item = createOrderItem(result);

        orderList.appendChild(item);

        apiForm.reset();


    } catch (error) {
        console.error(error);
        createStatus.textContent = error.message;
    }
});