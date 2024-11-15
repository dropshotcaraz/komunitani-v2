<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- Search Form -->
            <form id="search-form" method="GET" action="{{ route('search') }}" class="mb-6">
                <div class="flex">
                    <input 
                        type="text" 
                        name="query"
                        id="search-input" 
                        placeholder="Search posts, topics, or keywords" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ request('query') ?? '' }}"
                    >
                    <button 
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700 transition"
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
                                {{ request('topic') == $topic ? 'selected' : '' }}
                            >
                                {{ $topic }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Date Filter -->
                    <select 
                        name="date_filter" 
                        id="date-filter" 
                        class="px-6 py-1 border border-gray-300 rounded"
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
                                <img src="{{ asset('storage/public/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-32 object-cover rounded-md mb-2">
                            @endif
                            
                            <h3 class="font-bold text-lg text-gray-800">{{ $post->user->name }}</h3>
                            <p class="text-gray-600 mt-2">{{ Str::limit($post->content, 100) }}</p>
                            
                            @if($post->topic)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mt-2">
                                    {{ $post->topic }}
                                </span>
                            @endif
                            
                            <div class="text-sm text-gray-500 mt-2">
                                {{ $post->created_at->diffForHumans() }}
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
            const dateFilter = document.getElementById('date-filter');

            // Submit form on filter change
            [topicFilter, dateFilter].forEach(filter => {
                filter.addEventListener('change', () => {
                    searchForm.submit();
                });
            });
        });
    </script>
    @endpush
</x-app-layout>