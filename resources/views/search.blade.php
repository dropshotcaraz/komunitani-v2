<x-app-layout>
    <div class="container max-w-6xl mx-auto px-4 py-8">
        <div class="bg-[#F7F0CF] rounded-2xl shadow-lg p-6">
            <!-- Search Form -->
            <form id="search-form" method="GET" action="{{ route('search') }}" class="mb-6">
                <div class="flex">
                    <input 
                        type="text" 
                        name="query"
                        id="search-input" 
                        placeholder="Search posts, topics, titles, or keywords" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ request('query') ?? '' }}"
                    >
                    <button 
                        type="submit"
                        class="bg-[#434028] text-white px-4 py-2 rounded-r-md hover:bg-blue-700 transition"
                    >
                        Search
                    </button>
                </div>

                <!-- Advanced Filters -->
                <div class="mt-4 flex space-x-4">
                    <!-- Topic Filter -->
                    <select 
                        name="topic" 
                        id="topic-filter" 
                        class="px-6 py-1 border border-gray-300 rounded"
                    >
                        <option value="">All Topics</option>
                        @foreach($availableTopics as $topic)
                            <option 
                                value="{{ $topic }}" 
                                {{ request('Topik') == $topic ? 'selected' : '' }}
                            >
                                {{ $topic }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Post Type Filter -->
                    <select 
                        name="post_type" 
                        id="post-type-filter" 
                        class="px-6 py-1 border border-gray-300 rounded"
                    >
                        <option value="">All Post Types</option>
                        @foreach($availablePostTypes as $postType)
                            <option 
                                value="{{ $postType }}" 
                                {{ request('Tipe Post') == $postType ? 'selected' : '' }}
                            >
                                {{ $postType }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Date Filter -->
                    <select 
                        name="date_filter" 
                        id="date-filter" 
                        class="px-4 py-1 border border-gray-300 rounded"
                    >
                        <option value="">Any Time</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
            </form>

            <!-- Search Results -->
            <div id="search-results" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    <div 
                        class="bg-white rounded-lg shadow hover:shadow-lg transition transform hover:scale-105 cursor-pointer"
                        onclick="window.location='{{ url('/posts/'.$post->id) }}'"
                    >
                        <div class="p-4">
                            <!-- Thumbnail Image -->
                            @if($post->image_path)
                                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-32 object-cover rounded-md mb-2">
                            @endif
                            
                            <h3 class="font-bold text-xl text-gray-800 mb-1">{{ $post->user->name }}</h3>
                            <p class="font-bold text-sm text-gray-800">{{ $post->title }}</h>
                            <p class="text-gray-600 mb-2">{{ Str::limit($post->content, 100) }}</p>
                            
                            <div class="flex justify-between items-center mt-2">
                                @if($post->topic)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ $post->topic }}
                                    </span>
                                @endif

                                @if($post->post_type)
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                        {{ $post->post_type }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-center text-sm text-gray-500 mt-2">
                                <span>{{ $post->user->name }}</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500">
                        No results found. Try a different search term.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $posts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('search-form');
            const topicFilter = document.getElementById('topic-filter');
            const postTypeFilter = document.getElementById('post-type-filter');
            const dateFilter = document.getElementById('date-filter');

            // Submit form on filter change
            [topicFilter, postTypeFilter, dateFilter].forEach(filter => {
                filter.addEventListener('change', () => {
                    searchForm.submit();
                });
            });
        });
    </script>
    @endpush
</x-app-layout>