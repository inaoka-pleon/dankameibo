<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <!-- <link rel="stylesheet" href="/css/app.css" > -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')

    <button type="button">
        <a href="{{ route('temple.index') }}">
            戻る
        </a>
    </button>

    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex  flex-col justify-center items-center z-10"></div>
        <div class="flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">寺院詳細</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" >
                <div class="">
                    <div class="">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" >
            <div class="">
                <div class="">  
                    <table class="tableSample">
                        <tr>
                            <th>宗務所</th>
                            <td>{{ $temple->templeoffice }}</td>
                        </tr>
                        <tr>
                            <th>教区</th>
                            <td>{{ $temple->parish }}</td>
                        </tr>
                        <tr>
                            <th>寺籍番号</th>
                            <td>{{ $temple->no }}</td>
                        </tr>
                        <tr>
                            <th>寺院名</th>
                            <td>{{ $temple->templename }}</td>
                        </tr>
                        <tr>
                            <th>寺院名かな</th>
                            <td>{{ $temple->templenamekana }}</td>
                        </tr>
                        <tr>
                            <th>資格</th>
                            <td>{{ $temple->qualification }}</td>
                        </tr>
                        <tr>
                            <th>氏名</th>
                            <td>{{ $temple->name }}</td>
                        </tr>
                        <tr>
                            <th>ふりかな</th>
                            <td>{{ $temple->namekana }}</td>
                        </tr>
                        <tr>
                            <th>敬称</th>
                            <td>{{ $temple->title }}</td>
                        </tr>
                        <tr>
                            <th>脇敬称</th>
                            <td>{{ $temple->subtitle }}</td>
                        </tr>
                        <tr>
                            <th>郵便番号</th>
                            <td>{{ $temple->postcode }}</td>
                        </tr>
                        <tr>
                            <th>住所１</th>
                            <td>{{ $temple->address1 }}</td>
                        </tr>
                        <tr>
                            <th>住所２</th>
                            <td>{{ $temple->address2 }}</td>
                        </tr>
                        <tr>
                            <th>電話番号</th>
                            <td>{{ $temple->tel }}</td>
                        </tr>
                        <tr>
                            <th>FAX
                            <td>{{ $temple->fax }}</td>
                        </tr>
                        <tr>
                            <th>手紙区分</th>
                            <td>{{ $temple->letterdivision }}</td>
                        </tr>
                        <tr>
                            <th>年賀状区分</th>
                            <td>{{ $temple->newyearscarddivision }}</td>
                        </tr>
                        <tr>
                            <th>暑中見舞区分</th>
                            <td>{{ $temple->summergreetingdivision }}</td>
                        </tr>
                        <tr>
                            <th>師</th>
                            <td>{{ $temple->teacher }}</td>
                        </tr>
                        <tr>
                            <th>山号</th>
                            <td>{{ $temple->mountainname }}</td>
                        </tr>
                        <tr>
                            <th>寺格</th>
                            <td>{{ $temple->jikaku }}</td>
                        </tr>
                        <tr>
                            <th>仏教会</th>
                            <td>{{ $temple->buddhistfederation }}</td>
                        </tr>
                        <tr>
                            <th>入会名</th>
                            <td>{{ $temple->nyuukai_name }}</td>
                        </tr>
                        <tr>
                            <th>備考</th>
                            <td>{{ $temple->memo }}</td>
                        </tr>
                    </table>

                    <hr>
                    <div class="h3">
                            <h3 class="h3-item">寺院情報</h3>
                            <button type="button">
                                        <a href="{{ route('member.create', $temple->id) }}" class="btn">
                                            寺院情報登録
                                        </a>
                                    </button>
                        </div>
                    <div class="jiintabs">

                    @if ($informations->isNotEmpty()) 
                        <table class="table-danka radius-table">
                            <thead>
                                <tr>
                                    <th class="name1">氏名</th>
                                    <th class="namekana1">氏名かな</th>
                                    <th class="qualification">資格</th>
                                    <th scope="col">
                                        <span></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($informations as $information)
                                    <tr>
                                        <td>
                                            <a href="{{ route('member.show', $information->id) }}">{{ $information->name }}</a>
                                        </td>
                                        <td>{{ $information->namekana }}</td>
                                        <td>{{ $information->qualification }}</td>
                                        <td>
                                            <div class="btn-center">
                                                <a href="{{ route('member.edit', $information->id )}}" class="btn-edit">
                                                    <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                                </a>
                                                <a href="{{ route('temple.chiefpriest.change', ['temple_id' => $temple->id, 'id' => $information->id]) }}" class="btn-other"
                                                    onclick="return confirm('住職を交代します。よろしいですか？')">住職交代</a>
                                                <form onsubmit="return deleteInformation();"
                                                    class="btn-delete"
                                                    action="{{ route('member.destroy', ['temple_id' => $information->temple_id, 'id' => $information->id]) }}" method="post"
                                                    role="menuitem" tabindex="-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">
                                                        <i class="fa-solid fa-trash"></i><span class="mx-2">削除</span>
                                                    </button> 
                                                </form>
                                            </div>
                                        </td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>

                    @else
                        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                            <p class="font-bold">寺院情報がありません</p>
                        </div>
                    @endif
                    </div>
                    <hr>
                        
                    <!-- <a class="form-btn2 form-inline-block" href="{{ route('danka.index') }}">戻る</a> -->
                </div>
            </div>
        </div>
    </div>

<script>
    function deleteInformation(){
        if(confirm('削除します。よろしいですか？')){
            return true;
        } else {
            return false;
        }
    }
</script>

</x-app-layout>