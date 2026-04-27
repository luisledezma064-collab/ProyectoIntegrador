// Cambiar entre Tarjeta y Efectivo
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.querySelectorAll('.toggle-btn').forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}

// Agregar tarjeta a la lista
function addCard() {
    const cardNum = document.getElementById('card-num').value;
    const cardsList = document.getElementById('cards-list');

    if (cardNum.length < 10) {
        alert("Por favor ingresa un número de tarjeta válido");
        return;
    }

    const last4 = cardNum.slice(-4);
    
    const newCard = document.createElement('div');
    newCard.className = 'card-item';
    newCard.innerHTML = `
        <div class="card-brand"><i class="ph-fill ph-credit-card" style="color: #6366f1;"></i></div>
        <span class="card-digits">**** **** **** ${last4}</span>
        <button class="delete-btn" onclick="removeCard(this)"><i class="ph ph-trash"></i></button>
    `;

    cardsList.appendChild(newCard);
    
    // Limpiar formulario
    document.getElementById('payment-form').reset();
}

// Eliminar tarjeta
function removeCard(button) {
    if(confirm("¿Estás seguro de eliminar este método de pago?")) {
        button.parentElement.remove();
    }
}