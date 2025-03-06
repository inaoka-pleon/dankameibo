<div>
    <!-- When there is no desire, all things are at peace. - Laozi -->
</div>
<!-- resources/views/components/postcard-form.blade.php -->
<form action="{{ route($actionRoute, $data ? $data->id : '') }}" method="POST">
    @csrf
    @if ($method == 'PATCH')
        @method('PATCH')
    @endif

    <div>
        <table class="table">
            <tr>
                <th class="registration-item">表題</th>
                <td class="registration-body">
                    <input type="text" name="title" class="registration-input" placeholder="表題" value="{{ old('title', $data->title ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">文１</th>
                <td class="registration-body">
                    <input type="text" name="document1" class="registration-input" placeholder="３５文字以内で入力してください" value="{{ old('document1', $data->document1 ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">文２</th>
                <td class="registration-body">
                    <input type="text" name="document2" class="registration-input" placeholder="３５文字以内で入力してください" value="{{ old('document2', $data->document2 ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">文３</th>
                <td class="registration-body">
                    <input type="text" name="document3" class="registration-input" placeholder="３５文字以内で入力してください" value="{{ old('document3', $data->document3 ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">文４</th>
                <td class="registration-body">
                    <input type="text" name="document4" class="registration-input" placeholder="３５文字以内で入力してください" value="{{ old('document4', $data->document4 ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">文５</th>
                <td class="registration-body">
                    <input type="text" name="document5" class="registration-input" placeholder="３５文字以内で入力してください" value="{{ old('document5', $data->document5 ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">文６</th>
                <td class="registration-body">
                    <input type="text" name="document6" class="registration-input" placeholder="３５文字以内で入力してください" value="{{ old('document6', $data->document6 ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">文７</th>
                <td class="registration-body">
                    <input type="text" name="document7" class="registration-input" placeholder="３５文字以内で入力してください" value="{{ old('document7', $data->document7 ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">文８</th>
                <td class="registration-body">
                    <input type="text" name="document8" class="registration-input" placeholder="３５文字以内で入力してください" value="{{ old('document8', $data->document8 ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">文９</th>
                <td class="registration-body">
                    <input type="text" name="document9" class="registration-input" placeholder="３５文字以内で入力してください" value="{{ old('document9', $data->document9 ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">各位</th>
                <td class="registration-body">
                    <input type="text" name="kakui" class="registration-input" placeholder="各位" value="{{ old('kakui', $data->kakui ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">住所</th>
                <td class="registration-body">
                    <input type="text" name="address" class="registration-input" placeholder="住所" value="{{ old('address', $data->address ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">寺院名</th>
                <td class="registration-body">
                    <input type="text" name="templename" class="registration-input" placeholder="寺院名" value="{{ old('templename', $data->templename ?? '') }}" />
                </td>
            </tr>
            <tr>
                <th class="registration-item">TEL</th>
                <td class="registration-body">
                    <input type="text" name="tel" class="registration-input" placeholder="電話番号" value="{{ old('tel', $data->tel ?? '') }}" />
                </td>
            </tr>
        </table>
        <br>
    </div>
</form>