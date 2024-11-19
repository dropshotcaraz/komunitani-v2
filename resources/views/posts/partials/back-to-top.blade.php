<!-- Back to Top Button -->
<button id="backToTop" class="fixed bottom-8 right-8 p-3 rounded-full bg-[#6FA843] hover:bg-[#578432] text-white shadow-lg transition-all duration-300 opacity-0 translate-y-12 pointer-events-none">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<script>
    // Back to Top Button Logic
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0', 'translate-y-12', 'pointer-events-none');
            backToTopButton.classList.add('opacity-100', 'translate-y-0');
        } else {
            backToTopButton.classList.add('opacity-0', 'translate-y-12', 'pointer-events-none');
            backToTopButton.classList.remove('opacity-100', 'translate-y-0');
        }
    });

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>