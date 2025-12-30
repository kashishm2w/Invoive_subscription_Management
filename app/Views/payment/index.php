<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

<h2>Pay with Stripe</h2>

<input type="text" id="name" placeholder="Customer Name"><br><br>
<input type="number" id="amount" placeholder="Amount (₹)"><br><br>

<div id="card-element"></div><br>
<button id="payBtn">Pay Now</button>

<script>
const stripe = Stripe("pk_test_XXXXXXXXXXXXXXXX");
const elements = stripe.elements();
const card = elements.create("card");
card.mount("#card-element");

document.getElementById("payBtn").addEventListener("click", async () => {

    const response = await fetch("create-payment.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            name: document.getElementById("name").value,
            amount: document.getElementById("amount").value
        })
    });

    const data = await response.json();

    const result = await stripe.confirmCardPayment(data.clientSecret, {
        payment_method: {
            card: card
        }
    });

    if (result.error) {
        alert(result.error.message);
    } else {
        window.location.href = "success.php";
    }
});
</script>

</body>
</html>
