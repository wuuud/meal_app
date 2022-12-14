<x-app-layout>
    <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-white shadow-md">
        <h2 class="text-center text-lg font-bold pt-6 tracking-widest">食事記録編集</h2>

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

        {{-- タイトル --}}
        <form action="{{ route('meals.update', $meal) }}" method="POST" enctype="multipart/form-data"
            class="rounded pt-3 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="title" placeholder="タイトル">
                    タイトル
                </label>
                <input type="text" name="title"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full py-2 px-3"
                    placeholder="タイトル" value="{{ old('title', $meal->title) }}">
            </div>

            {{-- ラジオボタンhttps://qiita.com/yusuke___web/items/9ee65ef9f25045c12284--}}
            {{-- <div class="form-group">
                <label>{{ __('カテゴリー') }}
                    <div class="form-check form-check-inline">
                        <input type="radio" name="category_id" class="form-check-input" id="release1" value="野菜"
                            {{ old('category_id', $meal->category_id) == '野菜' ? 'checked' : '' }}>
                        <label for="release1" class="form-check-label">野菜</label>
                    </div> 
                    <div class="form-check form-check-inline">
                        <input type="radio" name="category_id" class="form-check-input" id="release2" value="タンパク質" 
                            {{ old('category_id', $meal->category_id) == 'タンパク質' ? 'checked' : '' }}>
                        <label for="release2" class="form-check-label">タンパク質</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="category_id" class="form-check-input" id="release3" value="炭水化物"
                            {{ old('category_id', $meal->category_id) == '炭水化物' ? 'checked' : '' }}> 
                        <label for="release3" class="form-check-label">炭水化物</label>
                    </div>
                </label>
            </div>  --}}
            {{-- https://qiita.com/gyu_outputs/items/d0ba64928972b7b47582 --}}
            <div class="form-group">
                <label for="category" class="block text-gray-700 text-sm mb-2">
                    カテゴリー
                </label>
                @foreach ($categories as $category)
                <div class="form-check form-check-inline text-gray-700 text-sm mb-2">
                    <p>
                    <input type="radio" name="list" id="category{{ $category->id }}" class="form-check-input" 
                            value= "{{ $category->id }}"
                            @if (old("category_id", $meal->category_id) == $category->id) checked @endif />
                    <label for="category{{ $category->id }}">{{ $category->list }}</label>
                    </p>
                </div>
                @endforeach
            </div>
            {{-- 詳細 --}}
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="body" placeholder="詳細">
                    詳細
                </label>
                <textarea name="body" rows="10"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full py-2 px-3"
                    placeholder="詳細">{{ old('body', $meal->body) }}</textarea>
            </div>
            {{-- ①お気に入り数の表示 --}}
                    <p class="mt-2 mb-xl-4 display:flex flex-wrap:wrap text-sm mb-2 md:text-base font-normal">
                    <span class="text-blue-600 font-bold">お気に入り数：{{ $meal->likes->count() }}</span>
            {{-- 画像 --}}
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="image">
                    食事用の画像
                </label>
                {{-- $meal->image_urlを使って読み込み --}}
                <img src="{{ $meal->image_url }}" alt="" class="mb-4 md:w-2/5 sm:auto">
                <input type="file" name="image" class="border-gray-300">
            </div>
            <input type="submit" value="更新"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        </form>
    </div>
</x-app-layout>
