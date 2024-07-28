<nav class="flex flex-row py-4 px-6 justify-between items-center bg-slate-100">
    <img src="assets/images/logodark.png" alt="Logo" class="h-14 w-auto">
    
    <!-- Hamburger Menu for Mobile -->
    <div class="sm:hidden flex items-center">
        <input type="checkbox" id="menu-toggle" class="hidden">
        <label for="menu-toggle" class="cursor-pointer flex items-center">
            <svg id="hamburger-icon" class="w-6 h-6 block" fill="none" stroke="gray" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="gray" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </label>
    </div>
    
    <!-- Navigation Links -->
    <div class='flex flex-grow justify-evenly max-w-xl hidden sm:flex' id="nav-links">
        <a href="/" class="flex flex-col items-center cursor-pointer group w-12 sm:w-20 text-gray-500 hover:text-gray-800">
            <i data-lucide="home" class="h-10 mb-1 group-hover:animate-bounce" style="color: gray;"></i>
            <p class="tracking-widest font-semibold">BERANDA</p>
        </a>
        <a href="/" class="flex flex-col items-center cursor-pointer group w-12 sm:w-20 text-gray-500 hover:text-gray-800">
            <i data-lucide="headphones" class="h-10 mb-1 group-hover:animate-bounce" style="color: gray;"></i>
            <p class=" tracking-widest font-semibold">PENGADUAN</p>
        </a>
        <a href="/" class="flex flex-col items-center cursor-pointer group w-12 sm:w-20 text-gray-500 hover:text-gray-800">
            <i data-lucide="book-open-text" class="h-10 mb-1 group-hover:animate-bounce" style="color: gray;"></i>
            <p class=" tracking-widest font-semibold">PANDUAN</p>
        </a>
        <a href="/" class="flex flex-col items-center cursor-pointer group w-12 sm:w-20 text-gray-500 hover:text-gray-800">
            <i data-lucide="message-circle" class="h-10 mb-1 group-hover:animate-bounce" style="color: gray;"></i>
            <p class=" tracking-widest font-semibold">FAQ</p>
        </a>
    </div>
</nav>
<!-- Mobile Navigation Links -->
<div class="sm:hidden bg-slate-100 flex flex-col w-full hidden gap-2 p-4" id="mobile-nav">
    <div class="flex flex-row items-center cursor-pointer group w-full text-white gap-3">
        <i data-lucide="home" class="h-10 mb-1" style="color: gray;"></i>
        <a class="tracking-widest text-gray-500 font-semibold">BERANDA</a>
    </div>
    <div class="flex flex-row items-center cursor-pointer group w-full text-white gap-3">
        <i data-lucide="headphones" class="h-10 mb-1" style="color: gray;"></i>
        <a class="tracking-widest text-gray-500 font-semibold">PENGADUAN</a>
    </div>
    <div class="flex flex-row items-center cursor-pointer group w-full text-white gap-3">
        <i data-lucide="book-open-text" class="h-10 mb-1" style="color: gray;"></i>
        <p class="tracking-widest text-gray-500 font-semibold">PANDUAN</p>
    </div>
    <div class="flex flex-row  items-center cursor-pointer group w-full text-white gap-3">
        <i data-lucide="message-circle" class="h-10 mb-1" style="color: gray;"></i>
        <p class="tracking-widest text-gray-500 font-semibold">FAQ</p>
    </div>
</div>


<!-- Add this JavaScript to toggle the mobile nav -->
<script>
    const menuToggle = document.getElementById('menu-toggle');
    const navLinks = document.getElementById('nav-links');
    const mobileNav = document.getElementById('mobile-nav');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');

    menuToggle.addEventListener('change', function() {
        if (menuToggle.checked) {
            mobileNav.style.display = 'flex';
            hamburgerIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
        } else {
            mobileNav.style.display = 'none';
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });
</script>
