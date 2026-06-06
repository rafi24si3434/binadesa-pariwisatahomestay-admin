<!-- Floating WhatsApp Button -->
{{-- ============================= --}}
{{--       GLOBAL FOOTER          --}}
{{-- ============================= --}}
<footer class="mt-5 py-4 bg-white border-top shadow-sm fade-in">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">

        {{-- LEFT SIDE --}}
        <div class="text-center text-md-start mb-3 mb-md-0">
            <h6 class="fw-bold mb-0" style="color:#C62828;">
                Sistem Informasi Pariwisata & Homestay
            </h6>
            <small class="text-muted">
                © <span id="year"></span> Bina Desa — All Rights Reserved
            </small>
        </div>

        {{-- RIGHT SIDE SOCIAL ICONS --}}
        <div class="d-flex align-items-center" style="gap:18px; font-size:22px;">

            <a href="https://instagram.com" target="_blank" class="text-danger" title="Instagram">
                <i class="fa-brands fa-instagram"></i>
            </a>

            <a href="https://facebook.com" target="_blank" class="text-primary" title="Facebook">
                <i class="fa-brands fa-facebook"></i>
            </a>

            <a href="https://youtube.com" target="_blank" class="text-danger" title="YouTube">
                <i class="fa-brands fa-youtube"></i>
            </a>

            <a href="https://github.com" target="_blank" class="text-dark" title="Github">
                <i class="fa-brands fa-github"></i>
            </a>

        </div>

    </div>
</footer>

{{-- DYNAMIC YEAR --}}
<script>
    document.getElementById("year").innerText = new Date().getFullYear();
</script>


<a href="https://wa.me/6289524214721" target="_blank" id="whatsapp-float">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" width="45"
        height="45">
</a>

<style>
    #whatsapp-float {
        position: fixed;
        bottom: 25px;
        right: 25px;
        background-color: #25D366;
        border-radius: 50%;
        padding: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        z-index: 9999;
        transition: all 0.3s ease-in-out;
    }

    #whatsapp-float img {
        display: block;
    }

    #whatsapp-float:hover {
        transform: scale(1.1);
        background-color: #20b955;
    }
</style>

{{-- EndWa --}}
