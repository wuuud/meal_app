
{{-- ラジオボタンhttps://qiita.com/yusuke___web/items/9ee65ef9f25045c12284 --}}
<div class="form-group">
    <label>{{ __('投稿') }}
    <div class="form-check form-check-inline">
        <input type="radio" name="release" class="form-check-input" id="release1" value="投稿しない" {{ old ('release', $menu->release) == '投稿しない' ? 'checked' : '' }}>
        <label for="release1" class="form-check-label">投稿しない</label>
    </div>
    <div class="form-check form-check-inline">
        <input type="radio" name="release" class="form-check-input" id="release2" value="投稿する" {{ old ('release', $menu->release) == '投稿する' ? 'checked' : '' }}>
        <label for="release2" class="form-check-label">投稿する</label>
    </div>
    </label>
</div>
