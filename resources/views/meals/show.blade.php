<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-8 py-4 bg-white shadow-md">
        @if (session('notice'))
            <div class="bg-blue-100 border-blue-500 text-blue-700 border-l-4 p-4 my-2">
                {{ session('notice') }}
            </div>
        @endif

        {{-- @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-2" role="alert">
                <p>
                    <b>{{ count($errors) }}件のエラーがあります。</b>
                </p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <x-validation-errors :errors="$errors" />

        <article class="mb-2">
            <h1 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl">{{ $meal->title }}
            </h1>
            <p>
                <span class="text-gray-600">投稿者：</span>
                <span class="font-bold">{{ $meal->user->name }}</span>
            </p>
            {{-- 現在時刻 --}}
            <div>
                <p class="display:flex flex-wrap:wrap text-sm mb-2 md:text-base font-normal text-gray-600">
                    <span class="text-gray-600">現在時刻：</span>
                    <span class="text-red-400 font-bold width: 50%">{{ $today }}</span>
                </p>
            </div>
            {{-- 記事作成日 --}}
            <p class="text-sm mb-2 md:text-base font-normal text-gray-600">
                <span
                    class="text-red-400 font-bold">{{ date('Y-m-d H:i:s', strtotime('-1 day')) < $meal->created_at ? 'NEW' : '' }}</span>
                記事作成日：{{ $meal->created_at }}
            </p>
            {{-- 画像 --}}
            {{-- 変更前① <img src="{{ Storage::url('images/meals/' . $meal->image) }}"> 
            Meal.php Mealモデルに登録
            public function image_url(){
            return Storage::url('images/meals/' . $this->image);}
            変更前② <img src="{{ $meal->image_url() }}">
            title,body,imageのように()がなくても呼び出せるようにモデルにアクセサを定義前 --}}
            {{-- public function getImageUrlAttribute()
    {
        return Storage::url('images/meals/' . $this->image);
    } --}}
            <img src="{{ $meal->image_url }}" alt="" class="mb-4">
            <p class="text-gray-700 text-base">{!! nl2br(e($meal->body)) !!}</p>
        </article>
        <div class="flex flex-row text-center my-4">
            {{-- ポリシー適応前  
            <a href="{{ route('meals.edit', $meal) }}"
                class="bg-violet-600 hover:bg-violet-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20 mr-2">編集</a>
            <form action="{{ route('meals.destroy', $meal) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20">
            </form> --}}
            {{-- 下記ポリシー適応後 ポリシー適応するために@can @endcanを各アクションごとに記載 --}}
            @can('update', $meal)
                <a href="{{ route('meals.edit', $meal) }}"
                    class="bg-violet-600 hover:bg-violet-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20 mr-2">編集</a>
            @endcan
            @can('delete', $meal)
                <form action="{{ route('meals.destroy', $meal) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20">
                </form>
            @endcan

        </div>
    </div>
</x-app-layout>
