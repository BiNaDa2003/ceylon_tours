/* ==========================================================================
   Ceylon Tours Tours Custom Scripts
   ========================================================================== */

document.addEventListener('DOMContentLoaded', function () {

    // 1. Form Validation (Bootstrap standard)
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })

    // 2. Navbar Scroll Effect
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                navbar.classList.add('shadow');
            } else {
                navbar.classList.remove('shadow');
            }
        });
    }

    // 3. Initialize Toasts
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl, { autohide: true, delay: 4000 })
    })
    toastList.forEach(toast => toast.show());

});

// 4. Newsletter Subscription Mock (Frontend only feature request)
function subscribeNewsletter(e) {
    e.preventDefault();
    const email = document.getElementById('newsletter-email').value;
    const msg = document.getElementById('newsletter-msg');

    if (email) {
        msg.style.display = 'block';
        msg.innerText = "Thanks for subscribing! We'll keep you updated.";
        msg.classList.remove('text-danger');
        msg.classList.add('text-success');
        document.getElementById('newsletter-email').value = '';

        setTimeout(() => {
            msg.style.display = 'none';
        }, 5000);
    }
}
