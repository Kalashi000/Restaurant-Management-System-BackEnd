// --- 1. Filter Logic ---
const filterButtons = document.querySelectorAll('.filter-btn');
const foodCards = document.querySelectorAll('.food-card');

filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        const filterValue = button.getAttribute('data-filter');
        foodCards.forEach(card => {
            if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// --- 2. Search Logic ---
const searchInput = document.getElementById('menu-search');
if (searchInput) {
    searchInput.addEventListener('keyup', (e) => {
        const searchText = e.target.value.toLowerCase();
        foodCards.forEach(card => {
            const foodName = card.querySelector('h3').innerText.toLowerCase();
            if (foodName.includes(searchText)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
}

// --- 3. Order Selection Logic ---
const orderButtons = document.querySelectorAll('.order-btn');
const checkoutSection = document.getElementById('checkout-section');
const selectedFoodText = document.getElementById('selected-food');

orderButtons.forEach(button => {
    button.addEventListener('click', () => {
        const foodCard = button.closest('.food-card');
        const foodName = foodCard.querySelector('h3').innerText;
        const foodPrice = foodCard.querySelector('.price').innerText;
        
        if(checkoutSection) {
            checkoutSection.style.display = 'block';
            selectedFoodText.innerHTML = "<strong>" + foodName + "</strong> - <strong>" + foodPrice + "</strong>";
            window.location.href = "#checkout-section";
        }
    });
});

// --- 4. Final Order Placement Logic ---
function placeFinalOrder() {
    const name = document.getElementById('cus-name').value;
    const address = document.getElementById('cus-address').value;
    const phone = document.getElementById('cus-phone').value;
    const paymentElement = document.querySelector('input[name="payment"]:checked');
    
    if (!paymentElement) {
        showSuccess("Please select a payment method.");
        return;
    }
    const paymentMethod = paymentElement.value;
    
    const fullDetails = selectedFoodText.innerText;
    const foodName = fullDetails.split(" - ")[0];
    const rawPrice = fullDetails.split(" - ")[1]; 

    // Validation
    if(name === "" || address === "" || phone === "") {
        showSuccess("Please fill all the details!");
        return;
    }

    const phonePattern = /^(?:0|94|\+94)?7(0|1|2|4|5|6|7|8)\d{7}$/;
    if(!phonePattern.test(phone)) {
        showSuccess("Please enter a valid phone number!");
        return;
    }

    // Prepare Object for JSON sending
    const orderData = {
        name: name,
        address: address,
        phone: phone,
        food: foodName,
        payment: paymentMethod
    };

    if(paymentMethod === 'card') {
        // If it's a Card Payment, temporarily save the previous data and show the PayHere Popup

        window.pendingOrder = orderData;
        document.getElementById('fake-payhere').style.display = 'flex';
        document.getElementById('pay-amount').innerText = "Amount: " + rawPrice;
    } else {
        // If Cash on Delivery, save directly to the database
        saveOrderToDB(orderData);
    }
}

// Function to handle the PHP request (AJAX/Fetch)
function saveOrderToDB(dataToSend) {
    fetch('save_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dataToSend)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            showSuccess("Thank you! Your order has been placed successfully.");
            setTimeout(() => location.reload(), 2000);
        } else {
            showSuccess("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showSuccess("Server Connection Error!");
    });
}

// --- 5. Mock Payment Logic ---
function finishMockPayment() {
    const popup = document.getElementById('fake-payhere');
    const cardInput = popup.querySelector('input[placeholder="Card Number"]');
    const expiryInput = document.getElementById('card-expiry');
    const cvcInput = popup.querySelector('input[placeholder="CVC"]');

    if (cardInput.value.trim() === "" || expiryInput.value.trim() === "" || cvcInput.value.trim() === "") {
        showSuccess("Please fill all card details!");
        return;
    }

    const payBtn = event.target;
    payBtn.innerText = "Processing...";
    payBtn.disabled = true;

    setTimeout(function() {
        payBtn.innerText = "Success";
        payBtn.style.background = "#27ae60"; 
        
        setTimeout(function() {
            document.getElementById('fake-payhere').style.display = 'none';
           
            if(window.pendingOrder) {
                saveOrderToDB(window.pendingOrder);
            }
        }, 1000); 
    }, 2000); 
}

function closeMockPayment() {
    document.getElementById('fake-payhere').style.display = 'none';
}

// --- 6. Custom Alert Logic ---
function showSuccess(message) {
    const modal = document.getElementById('custom-alert');
    const msgPara = document.getElementById('alert-message');
    const msgTitle = document.getElementById('alert-title');

    if (modal && msgPara) {
        msgPara.innerText = message;
        if (msgTitle) msgTitle.style.display = 'none';
        msgPara.style.fontWeight = "bold";

        if (message.includes("Invalid") || message.includes("Please") || message.includes("Error")) {
            msgPara.style.color = "#e74c3c"; 
        } else {
            msgPara.style.color = "#000000"; 
        }
        modal.style.display = 'flex';
    }
}

function closeCustomAlert() {
    document.getElementById('custom-alert').style.display = 'none';
}

// --- 7. Nav Active Link on Scroll ---
const sections = document.querySelectorAll('section, header');
const navLi = document.querySelectorAll('.nav-links a');

window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        if (pageYOffset >= (sectionTop - 120)) {
            current = section.getAttribute('id');
        }
    });
    navLi.forEach(a => {
        a.classList.remove('active');
        if (a.getAttribute('href').includes(current)) {
            a.classList.add('active');
        }
    });
});

// --- 8. Card Expiry Auto-Format ---
document.addEventListener("DOMContentLoaded", function () {
    const expiryField = document.getElementById('card-expiry');
    if (!expiryField) return;

    expiryField.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); 
        if (value.length > 4) value = value.substring(0, 4);
        if (value.length >= 3) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        } else if (value.length >= 2) {
            value = value.substring(0, 2) + '/';
        }
        e.target.value = value;
    });
});

// --- 9. Contact Form Logic ---
const contactForm = document.getElementById('contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault(); 
        const name = contactForm.querySelector('input[name="name"]').value;
        if (name === "") {
            showSuccess("Please fill all fields!");
        } else {
            showSuccess("Message sent successfully!");
            contactForm.reset(); 
        }
    });
}