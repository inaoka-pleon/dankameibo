<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

     <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">汎用マスタ / 編集</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-8">
        </div>
        <div class="registration">
            <form method="POST" action="{{ route('generalmaster.update', $general_master->id) }}" accept-charset="UTF-8" onsubmit="if(confirm('保存します。よろしいですか？')) {return true} else {return false};">

                @csrf
                @method('PATCH')

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4 mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <table class="registration-table">
                    <tbody>
                        <input type="hidden" name="title" id="title" value="{{ $title }}">
                        <tr>
                            <th class="registration-item" for="value1">名称
                                <span class="registration-item-required">必須</span>
                            </th>
                            <td class="registration-body">
                                <input name="value1" id="value1" class="registration-input" value="{{ $general_master->value1 }}" placeholder="{{ $title }} (１５文字以内)" >  
                            </td>
                        </tr>
                        <tr id="row2">
                            <th class="registration-item" for="value2">金額</th>
                            <td class="registration-body">
                                <input name="value2" id="value2" class="registration-input" value="{{ $general_master->value2 }}" placeholder="{{ $title }} （円）" >
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" href="{{ route('generalmaster.index') }}">
                        <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                    </a>
                    <div type="submit" class="form-btn1 form-inline-block">
                        <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">変更</span>
                    </div>
                </div>
            </form>
        </div>
     </main>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const title = document.getElementById('title');
            const row2 = document.getElementById('row2');

            // 初期表示状態を設定
            if (title.value === 'DANKAKAIHI' || title.value === 'GOZIKAIKAIHI') {
                row2.style.display = 'table-row';
            } else {
                row2.style.display = 'none';
            }

            title.addEventListener('change', function() {
                if(title.value === 'DANKAKAIHI' || title.value === 'GOZIKAIKAIHI') {
                    row2.style.display = 'table-row';
                } else {
                    row2.style.display = 'none';
                }
            });
        }); 
    </script>
</x-app-layout>