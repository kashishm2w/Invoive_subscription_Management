document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.product-form');
    const posterInput = document.querySelector('#poster');
    const posterImg = document.querySelector('#poster-img');

    //  Live poster preview 
    posterInput.addEventListener('change', function() {
        const file = posterInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                posterImg.src = e.target.result;
                posterImg.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            posterImg.src = '';
            posterImg.style.display = 'none';
        }
    });

    //  Inline validation 
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        form.querySelectorAll('.error-message').forEach(span => span.textContent = '');

        const name = form.querySelector('#name').value.trim();
        const description = form.querySelector('#description').value.trim();
        const price = parseFloat(form.querySelector('#price').value);
        const tax = parseFloat(form.querySelector('#tax_percent').value);
        const quantity = parseInt(form.querySelector('#quantity').value);
        const poster = posterInput.files[0];

        const nameRegex = /^[a-zA-Z0-9\s\-]{2,50}$/;
        const descRegex = /^[a-zA-Z0-9\s.,'-]{5,500}$/;

        if (!name) { form.querySelector('#name').nextElementSibling.textContent = "Product name is required."; isValid=false;}
        else if (!nameRegex.test(name)) { form.querySelector('#name').nextElementSibling.textContent = "Only letters, numbers, spaces, dashes (2-50 chars)."; isValid=false;}

        if (!description) { form.querySelector('#description').nextElementSibling.textContent = "Description is required."; isValid=false;}
        else if (!descRegex.test(description)) { form.querySelector('#description').nextElementSibling.textContent = "Invalid description (5-500 chars)."; isValid=false;}

        if (isNaN(price) || price <= 0) { form.querySelector('#price').nextElementSibling.textContent = "Price must be valid number."; isValid=false;}
        if (isNaN(tax) || tax < 0) { form.querySelector('#tax_percent').nextElementSibling.textContent = "Tax percent must be valid."; isValid=false;}
        if (isNaN(quantity) || quantity < 1 || quantity > 6) { form.querySelector('#quantity').nextElementSibling.textContent = "Quantity must be 1-6."; isValid=false;}

        if (poster) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(poster.type)) { form.querySelector('#poster').nextElementSibling.textContent = "Poster must be JPG, PNG, or GIF."; isValid=false;}
            else if (poster.size > 2*1024*1024) { form.querySelector('#poster').nextElementSibling.textContent = "Poster must be <2MB."; isValid=false;}
        }

        if (isValid) form.submit();
    });
});