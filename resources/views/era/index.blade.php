<x-app-layout>
    
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">元号設定</div>
                <div class="header-buttons">
                    <a class="btn-entry" href="{{ route('era.create') }}">
                        <i class="fa-solid fa-plus"></i><span class="mx-2">新規登録</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <hr class="w-full mb-4 max-w-7xl">
            <div class="w-full md:mb-4 max-w-7xl text-sm sm:text-base">
                <div class="">
                    @if($eras->count())
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="era_name">元号</th>
                                    <th class="era_year">年数</th>
                                    <th class="era_ad_start">開始西暦</th>
                                    <th class="era_start_ymd">開始年月日</th>
                                    <th class="era_end_ymd">終了年月日</th>
                                    <th class="era_col"></th>
                                    <th class="border px-4 py-2 text-xl table-cell md:hidden">
                                        元号　／　年数　／　開始西暦<br>
                                        開始年月日　／　終了年月日
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($eras as $era)
                                <tr>
                                    <td>{{ $era->name }}</td>
                                    <td>{{ $era->years }}</td>
                                    <td>{{ $era->ad_start }}</td>
                                    <td>{{ $era->start_ymd }}</td>
                                    <td>{{ $era->end_ymd }}</td>
                                    <td>
                                        <div class="btn-center">
                                            <a href="{{ route('era.edit', $era->id )}}" class="btn-edit">
                                                <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                            </a>
                                            <form onsubmit="return deleteEra();"
                                                class="btn-delete"
                                                action="{{ route('era.destroy', $era->id) }}" method="post"
                                                role="menuitem" tabindex="-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit">
                                                    <i class="fa-solid fa-trash"></i><span class="mx-2">削除</span>
                                                </button> 
                                            </form>
                                        </div>
                                    </td>
                                    <td class="border px-4 py-2 text-xl table-cell md:hidden">
                                        {{ $era->name }}　{{ $era->years }}　{{ $era->ad_start }}<br>
                                        {{ $era->start_ymd }}　-　{{ $era->end_ymd }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="pagination justify-content-center">
                            {{ $eras->links() }}
                        </div>
                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">元号情報がありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

<script>
    function deleteEra(){
        if(confirm('削除します。よろしいですか？')){
            return true;
        } else {
            return false;
        }
    }
</script>
</x-app-layout>

