<x-admin-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @include('errors.form_errors')

    <main class="mt-1 py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">元号設定</div>
                <div class="inline-flex -mt-1">
                    <a class="btn-entry" href="{{ route('admin.era.create') }}">
                        <i class="fa-solid fa-plus"></i><span class="mx-2">新規登録</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-8">
            <hr class="mb-4 w-full max-w-7xl">
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
                                    <th class="border text-xl px-4 py-2 visible md:hidden">
                                        元号<br>
                                        年数<br>
                                        開始西暦<br>
                                        開始年月日<br>
                                        終了年月日
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($eras as $era)
                                    <tr>
                                        <td class="era_name" data-th="元号">{{ $era->name }}</td>
                                        <td class="era_year" data-th="年数">{{ $era->years }}</td>
                                        <td class="era_ad_start" data-th="開始西暦">{{ $era->ad_start }}</td>
                                        <td class="era_start_ymd" data-th="開始年月日">{{ $era->start_ymd }}</td>
                                        <td class="era_end_ymd" data-th="終了年月日">{{ $era->end_ymd }}</td>
                                        <td class="era_col">
                                            <div class="btn-center">
                                                <a href="{{ route('admin.era.edit', $era->id )}}" class="btn-edit">
                                                    <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                                </a>
                                                <form onsubmit="return deleteEra();"
                                                    class="btn-delete"
                                                    action="{{ route('admin.era.destroy', $era->id) }}" method="post"
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
                                            {{ $era->name }}<br>
                                            {{ $era->years }}<br>
                                            {{ $era->ad_start }}<br>
                                            {{ $era->start_ymd }}<br>
                                            {{ $era->end_ymd }}
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