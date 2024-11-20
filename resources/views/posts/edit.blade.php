<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="justify-center py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <main class="col-span-12 md:col-span-9 p-4 mt-20 md:mt-0 w-auto">
                <div class="bg-[#6FA843] shadow-lg p-6 rounded-3xl mb-10 border border-gray-200">
                    <h1 class="font-bold text-white text-3xl mb-4">Edit Post</h1>

                    <form id="editPostForm" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <input type="text" name="title" placeholder="Judul Post" class="w-full bg-gray-50 p-3 rounded-lg outline-none border border-gray-300 focus:border-[#6FA843]" value="{{ old('title', $post->title) }}" required>
                            <textarea id="postTextarea" name="content" placeholder="Tulis pertanyaan atau informasi di sini" class="w-full bg-gray-50 p-3 rounded-lg outline-none border border-gray-300 focus:border-[#6FA843]" required>{{ old('content', $post->content) }}</textarea>

                            <div class="flex items-center space-x-3">
                                <select id="topicSelect" name="topic" class="border border-gray-300 py-2 px-8 rounded-lg bg-gray-50 focus:border-[#6FA843]">
                                    <option value="">Pilih Topik</option>
                                    <option value="Pertanian Umum" {{ $post->topic == 'Pertanian Umum' ? 'selected' : '' }}>Pertanian Umum</option>
                                    <option value="Teknik Pertanian" {{ $post->topic == 'Teknik Pertanian' ? 'selected' : '' }}>Teknik Pertanian</option>
                                    <option value="Tanaman Pangan" {{ $post->topic == 'Tanaman Pangan' ? 'selected' : '' }}>Tanaman Pangan</option>
                                    <option value="Hortikultura" {{ $post->topic == 'Hortikultura' ? 'selected' : '' }}>Hortikultura</option>
                                    <option value="Perkebunan" {{ $post->topic == 'Perkebunan' ? 'selected' : '' }}>Perkebunan</option>
                                </select>

                                <select id="postTypeSelect" name="post_type" class="border border-gray-300 py-2 px-8 rounded-lg bg-gray-50 focus:border-[#6FA843]">
                                    <option value="">Pilih Tipe Post</option>
                                    <option value="Informasi" {{ $post->post_type == 'Informasi' ? 'selected' : '' }}>Informasi</option>
                                    <option value="Tanya Jawab" {{ $post->post_type == 'Tanya Jawab' ? 'selected' : '' }}>Tanya Jawab</option>
                                    <option value="Diskusi" {{ $post->post_type == 'Diskusi' ? 'selected' : '' }}>Diskusi</option>
                                </select>

                                <button type="button" onclick="document.getElementById('imageUpload').click()" class="flex items-center text-gray-500 hover:text-[#6FA843] transition">
                                    <svg class="bg-white rounded-xl" width="40px" height="40px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25H12.0574C14.3658 1.24999 16.1748 1.24998 17.5863 1.43975C19.031 1.63399 20.1711 2.03933 21.0659 2.93414C21.9607 3.82895 22.366 4.96897 22.5603 6.41371C22.75 7.82519 22.75 9.63423 22.75 11.9426V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62178 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62178 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12C21.25 9.62178 21.2484 7.91356 21.0736 6.61358C20.9018 5.33517 20.5749 4.56445 20.0052 3.9948C19.4355 3.42514 18.6648 3.09825 17.3864 2.92637C16.0864 2.75159 14.3782 2.75 12 2.75C9.62178 2.75 7.91356 2.75159 6.61358 2.92637ZM12 8.75C10.2051 8.75 8.75 10.2051 8.75 12C8.75 13.7949 10.2051 15.25 12 15.25C13.7949 15.25 15.25 13.7949 15.25 12C15.25 10.2051 13.7949 8.75 12 8.75ZM7.25 12C7.25 9.37665 9.37665 7.25 12 7.25C14.6234 7.25 16.75 9.37665 16.75 12C16.75 14.6234 14.6234 16.75 12 16.75C9.37665 16.75 7.25 14.6234 7.25 12Z" fill="#1C274C"/>
                                    </svg>
                                </button>
                                <input type="file" id="imageUpload" name="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                            </div>
                            
                            <!-- Thumbnail Preview -->
                            @if($post->image_path)
                            <div id="thumbnailPreview" class="mt-4">
                                <img id="thumbnail" src="{{ asset("storage/public/{$post->image_path}") }}" alt="Current Image" class="w-full h-auto rounded-lg border border-gray-200">
                                <!-- Remove Image Button -->
                                <button type="button" name="remove_image" onclick="removeImage()" class="mt-2 bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                    Remove Image
                                </button>
                            </div>
                            @else
                            <div id="thumbnailPreview" class="mt-4 hidden">
                                <img id="thumbnail" src="" alt="Preview Image" class="w-full h-auto rounded-lg border border-gray-200">
                                <button type="button" name="remove_image" onclick="removeImage()" class="mt-2 bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                    Remove Image
                                </button>
                            </div>
                            @endif

                            <button type="submit" class="w-full mt-2 bg-[#F7F0CF] text-black px-4 py-2 rounded-lg hover:bg-[#578432] transition">Update Post</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Handle form submission
        document.getElementById('editPostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Post berhasil diupdate',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `/posts/${data.post_id}`; // Redirect to the updated post
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Ada kesalahan error',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });

        function previewImage(event) {
            const file = event.target.files[0];
            const thumbnail = document.getElementById('thumbnail');
            const thumbnailPreview = document.getElementById('thumbnailPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnail.src = e.target.result;
                    thumbnailPreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            const thumbnail = document.getElementById('thumbnail');
            const thumbnailPreview = document.getElementById('thumbnailPreview');
            
            thumbnail.src = '';
            thumbnailPreview.classList.add('hidden');
            document.getElementById('imageUpload').value = '';

            if (!document.getElementById('removeImageInput')) {
                const removeImageInput = document.createElement('input');
                removeImageInput.type = 'hidden';
                removeImageInput.name = 'remove_image';
                removeImageInput.value = 'true';
                removeImageInput.id = 'removeImageInput';
                document.querySelector('form').appendChild(removeImageInput);
            }
        }
    </script>
</x-app-layout>