const modal = document.getElementById('profileModal');
const btn = document.getElementById('editProfileBtn');
const close = document.querySelector('.close');

btn.onclick = () => modal.style.display = 'flex';
close.onclick = () => modal.style.display = 'none';

document.getElementById('profileForm').addEventListener('submit', function (e) {
    e.preventDefault();

    fetch('/user/update-profile', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('profileMsg').innerText = data.message;
        if (data.success) {
            setTimeout(() => location.reload(), 1000);
        }
    });
});
