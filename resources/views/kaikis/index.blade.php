<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')
    
    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">回忌設定</div>
                <div class="inline-flex -mt-1">
                    <a class="btn-entry" href="{{ route('kaiki.create') }}">
                        <i class="fa-solid fa-plus"></i><span class="mx-2">新規登録</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <hr class="w-full mb-4 max-w-7xl">
            <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                <div class="">
                    @if($kaikis->count())
                        <table class="table-danka radius-table">
                            <thead>
                            <tr>
                                <th class="kaiki_kubun">区分</th>
                                <th class="kaiki_kaiki">回忌</th>
                                <th class="kaiki_name">回忌名</th>
                                <th class="kaiki_taishou">対象</th>
                                <th class="kaiki_col"></th>
                                <th class="border text-xl px-4 py-2 visible md:hidden">
                                    区分<br>
                                    回忌<br>
                                    回忌名<br>
                                    対象<br>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($kaikis as $kaiki)
                                    <tr>
                                        <td>{{ disp_kaiki_kbn($kaiki->kaiki_kbn) }}</td>
                                        <td>{{ $kaiki->kaiki }}</td>
                                        <td>{{ $kaiki->kaiki_name }}</td>
                                        <td>{{ disp_on_off_name($kaiki->target_flg) }}</td>
                                        <td>
                                            <div class="btn-center">
                                                <a href="{{ route('kaiki.edit', $kaiki->id) }}" class="btn-edit">
                                                    <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                                </a>
                                                <form onsubmit="return deleteKaiki();"
                                                    class="btn-delete"
                                                    action="{{ route('kaiki.destroy', $kaiki->id) }}" method="post"
                                                    role="menuitem" tabindex="-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">
                                                        <i class="fa-solid fa-trash"></i><span class="mx-2">削除</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="border text-xl px-4 py-2 visible md:hidden">
                                            {{ disp_kaiki_kbn($kaiki->kbn) }}<br>
                                            {{ $kaiki->kaiki }}<br>
                                            {{ $kaiki->kaiki_name }}<br>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">回忌設定情報がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script>
        function deleteKaiki(){
            if(confirm('削除します。よろしいですか？')){
                return true;
            } else {
                return false;
            }
        }
    </script>
</x-app-layout>