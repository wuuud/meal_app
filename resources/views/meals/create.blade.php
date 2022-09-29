<x-app-layout>
    <div class="container lg:w-1/2 md:w-4/5 w-11/12 mx-auto mt-8 px-8 bg-white shadow-md">
        <h2 class="text-center text-lg font-bold pt-6 tracking-widest">食事記録投稿</h2>

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


        <form action="{{ route('meals.store') }}" method="POST" enctype="multipart/form-data"
            class="rounded pt-3 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="title">
                    タイトル
                </label>
                <input type="text" name="title"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full py-2 px-3"
                    required placeholder="タイトル" value="{{ old('title') }}">
            </div>
            {{-- ラジオボタン作成https://qiita.com/yusuke___web/items/9ee65ef9f25045c12284 --}}
            {{--                                       既定で一つを checked --}}
            {{-- <div class="form-group">
                <label>{{ __('カテゴリー') }}
                    <div class="form-check form-check-inline">
                        <input type="radio" name="category_id" class="form-check-input" id="release1" value="野菜"
                            
                            {{ old('category_id') == '野菜' ? 'checked' : '' }} checked>
                        <label for="release1" class="form-check-label">野菜</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="category_id" class="form-check-input" id="release2" value="タンパク質"
                            {{ old('category_id') == 'タンパク質' ? 'checked' : '' }}>
                        <label for="release2" class="form-check-label">タンパク質</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="category_id" class="form-check-input" id="release3" value="炭水化物"
                            {{ old('category_id') == '炭水化物' ? 'checked' : '' }}>
                        <label for="release3" class="form-check-label">炭水化物</label>
                    </div>
                </label>
            </div> --}}

            <div class="form-group">
                <label for="category" class="block text-gray-700 text-sm mb-2">
                    カテゴリー
                </label>
                @foreach ( $categories as $category )
                    <p>
                    {{-- label forとinput idが同じなら、クリック時に反応 --}}
                    <input type="radio" name="category" id="category{{ $category->id }}" 
                        value="{{ $category->id }}" required>
                    <label for="category{{ $category->id }}">{{ $category->list }}</label>
                    </p>
                @endforeach
            </div>

            <br>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="body">
                    詳細
                </label>
                <textarea name="body" rows="10"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full py-2 px-3"
                    required>{{ old('body') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-2" for="image">
                    食事の画像
                </label>
                {{-- ファイルを渡すため。typeはfile --}}
                <input type="file" name="image" class="border-gray-300">
            </div>
            <input type="submit" value="登録"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        </form>
    </div>
</x-app-layout>
