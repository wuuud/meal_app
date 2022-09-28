<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meals.index') }}
        </h2>
    </x-slot> --}}

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    記録一覧です
                </div>
            </div>
        </div> --}}
    {{-- @if (session('notice'))
           <div class="bg-blue-100 border-blue-500 text-blue-700 border-l-4 p-4 my-2">
               {{ session('notice') }}
           </div>
       @endif --}}
    <x-flash-message :message="session('notice')" />

    <div class="container max-w-7xl mx-auto px-4 md:px-12 pb-3 mt-3">
        <div class="flex flex-wrap -mx-1 lg:-mx-4 mb-4">
            @foreach ($meals as $meal)
                <article class="w-full px-4 md:w-1/2 text-xl text-gray-800 leading-normal">
                    <a href="{{ route('meals.show', $meal) }}">
                        <h2 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">
                            {{ $meal->title }}</h2>
                        <h3>{{ $meal->user->name }}</h3>
                        <p class="text-sm mb-2 md:text-base font-normal text-gray-600">
                            <span
                                class="text-red-400 font-bold">{{ date('Y-m-d H:i:s', strtotime('-1 day')) < $meal->created_at ? 'NEW' : '' }}</span>
                            {{ $meal->created_at }}
                        </p>
                        <img class="w-full mb-2" src="{{ $meal->image_url }}" alt="">
                        <p class="text-gray-700 text-base">{{ Str::limit($meal->body, 50) }}</p>                        
                        {{-- ①お気に入り数の表示 --}}
                    <p class="mt-2 mb-xl-4 display:flex flex-wrap:wrap text-sm mb-2 md:text-base font-normal">
                    <span class="text-blue-600 font-bold">お気に入り数：{{ $meal->likes->count() }}</span>
                
                    </a>
                </article>
            @endforeach
        </div>
        {{-- ページネーションのリンクを表示 --}}
        {{ $meals->links() }}
    </div>
</x-app-layout>
