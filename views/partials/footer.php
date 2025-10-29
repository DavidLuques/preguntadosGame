<footer class="bg-light text-dark text-center text-lg-start mt-auto">
    <div class="footer-content">
        <p>&copy; <?php echo date("Y"); ?> My Website. All rights reserved.</p>
        <nav class="footer-nav">
            <a href="/about">About Us</a> |
            <a href="/contact">Contact</a> |
            <a href="/privacy">Privacy Policy</a>
        </nav>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
</body>

</html>