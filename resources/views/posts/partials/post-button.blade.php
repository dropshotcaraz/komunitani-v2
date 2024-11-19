<div class="fixed bottom-6 right-6 flex flex-col items-center space-y-4">
    <!-- Back to Top Button -->
    <button id="backToTop" class="bg-[#6FA843] hover:bg-[#578432] text-white p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 translate-y-12 pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
    
    <!-- Create Post Button -->
    <button id="createPostBtn" class="bg-[#6FA843] hover:bg-[#578432] text-white p-4 rounded-full shadow-lg transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </button>
</div>

<!-- Modal -->
<div id="postModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-[#6FA843]">Buat Post Baru</h3>
            <button id="closeModal" class="text-gray-600 hover:text-gray-800">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="postForm" action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <!-- Title Input -->
            <div>
                <input type="text" name="title" placeholder="Judul Post" 
                    class="w-full p-3 rounded-lg border border-gray-300 focus:border-[#6FA843] focus:ring-1 focus:ring-[#6FA843] outline-none">
            </div>
            
            <!-- Content Textarea -->
            <div>
                <textarea name="content" placeholder="Tulis pertanyaan atau informasi di sini" rows="4"
                    class="w-full p-3 rounded-lg border border-gray-300 focus:border-[#6FA843] focus:ring-1 focus:ring-[#6FA843] outline-none"></textarea>
            </div>
            
            <!-- Topic and Post Type Selects -->
            <div class="flex gap-4">
                <select name="topic" class="flex-1 p-2 rounded-lg border border-gray-300 focus:border-[#6FA843] outline-none">
                    <option value="">Pilih Topik</option>
                    <option value="Pertanian Umum">Pertanian Umum</option>
                    <option value="Teknik Pertanian">Teknik Pertanian</option>
                    <option value="Tanaman Pangan">Tanaman Pangan</option>
                    <option value="Hortikultura">Hortikultura</option>
                    <option value="Perkebunan">Perkebunan</option>
                    <option value="Peternakan">Peternakan</option>
                </select>
                
                <select name="post_type" class="flex-1 p-2 rounded-lg border border-gray-300 focus:border-[#6FA843] outline-none">
                    <option value="">Pilih Tipe Post</option>
                    <option value="Informasi">Informasi</option>
                    <option value="Tanya Jawab">Tanya Jawab</option>
                    <option value="Diskusi">Diskusi</option>
                    <option value="Berita">Berita</option>
                </select>
            </div>
            
            <!-- Image Upload -->
            <div class="flex items-center gap-2">
                <label class="cursor-pointer flex items-center gap-2 text-gray-600 hover:text-[#6FA843] transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Tambah Gambar</span>
                    <input type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                </label>
            </div>
            
            <!-- Image Preview -->
            <div id="imagePreview" class="hidden mt-2">
                <img id="preview" src="" alt="Preview" class="max-h-48 rounded-lg">
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" id="cancelPost" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-[#6FA843] hover:bg-[#578432] text-white rounded-lg transition">
                    Kirim Post
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('postModal');
    const createBtn = document.getElementById('createPostBtn');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelPost');
    const imagePreview = document.getElementById('imagePreview');
    const preview = document.getElementById('preview');
    const backToTopButton = document.getElementById('backToTop');

    // Modal functions
    createBtn.addEventListener('click', function() {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        // Reset form
        document.getElementById('postForm').reset();
        imagePreview.classList.add('hidden');
        preview.src = '';
    }

    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Image preview function
    window.previewImage = function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // Back to Top Button Logic
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
});
</script>