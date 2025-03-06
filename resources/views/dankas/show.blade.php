<x-app-layout>

    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')
    
    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex  flex-col justify-center items-center z-10"></div>
        <div class="flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">檀家詳細</div>
                <div class="inline-flex -mt-1">
                    <a href="{{ route('danka.index') }}">
                        <button class="header-btn">終了</button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full mb-4 max-w-7xl">
        </div>
        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" >
                <div class="">
                    <div class="">
                            <table class="tableSample">
                                <tr>
                                    <th>地区名</th>
                                    <td>{{ $danka->area }}</td>
                                </tr>
                                <tr>
                                    <th>氏名</th>
                                    <td>{{ $danka->name }}</td>
                                </tr>
                                <tr>
                                    <th>氏名かな</th>
                                    <td>{{ $danka->namekana }}</td>
                                </tr>
                                <tr>
                                    <th>生年月日</th>
                                    <td>{{ $danka->birthdate }}</td>
                                </tr>
                                <tr>
                                    <th>性別</th>
                                    <td>{{ $danka->gender }}</td>
                                </tr>
                                <tr>
                                    <th>郵便番号</th>
                                    <td>{{ $danka->postcode }}</td>
                                </tr>
                                <tr>
                                    <th>住所１</th>
                                    <td>{{ $danka->address1 }}</td>
                                </tr>
                                <tr>
                                    <th>住所２</th>
                                    <td>{{ $danka->address2 }}</td>
                                </tr>
                                <tr>
                                    <th>電話番号</th>
                                    <td>{{ $danka->tel }}</td>
                                </tr>
                                <tr>
                                    <th>FAX</th>
                                    <td>{{ $danka->fax }}</td>
                                </tr>
                                <tr>
                                    <th>寺役職</th>
                                    <td>{{ $danka->position }}</td>
                                </tr>
                                <tr>
                                    <th>檀家区分</th>
                                    <td>{{ $danka->dankadivision }}</td>
                                </tr>
                                <tr>
                                    <th>位牌区分</th>
                                    <td>
                                        {{ $danka->mortuarytablet }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>職業</th>
                                    <td>{{ $danka->occupation }}</td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td class="checkbox-container">
                                        <input type="hidden" name="gozikai" value="0"> 
                                        <input type="checkbox" name="gozikai" value="1" @if(old('gozikai', $danka->gozikai)) checked @endif disabled> 護持会
                                        <input type="hidden" name="membershipfee" value="0"> 
                                        <input type="checkbox" name="membershipfee" value="1" @if(old('membershipfee', $danka->membershipfee)) checked @endif disabled> 会費
                                        <input type="hidden" name="report" value="0"> 
                                        <input type="checkbox" name="report" value="1" @if(old('report', $danka->report)) checked @endif disabled> 届出
                                        <input type="hidden" name="tanagyou" value="0"> 
                                        <input type="checkbox" name="tanagyou" value="1" @if(old('tanagyou', $danka->tanagyou)) checked @endif disabled> 棚経
                                        <input type="hidden" name="haruhigan" value="0"> 
                                        <input type="checkbox" name="haruhigan" value="1" @if(old('haruhigan', $danka->haruhigan)) checked @endif disabled> 春彼岸
                                        <input type="hidden" name="akihigan" value="0"> 
                                        <input type="checkbox" name="akihigan" value="1" @if(old('akihigan', $danka->akihigan)) checked @endif disabled> 秋彼岸
                                        <input type="hidden" name="hanamatsuri" value="0"> 
                                        <input type="checkbox" name="hanamatsuri" value="1" @if(old('hanamatsuri', $danka->hanamatsuri)) checked @endif disabled> 花まつり
                                    </td>
                                </tr>
                                <tr>
                                    <th>はがき区分</th>
                                    <td>{{ $danka->postcard }}</td>
                                </tr>
                                <tr>
                                    <th>備考</th>
                                    <td>{{ $danka->memo }}</td>
                                </tr>
                            </table>

                        <div class="flex flex-col justify-center items-center">
                            <hr class="w-full mb-4 mt-4 max-w-7xl">
                        </div>

                        <div class="tabs">
                            <input id="family" type="radio" name="tab_item" {{ request('tab') !== 'kakocho' ? 'checked' : '' }} >
                            <label class="tab_item" for="family">家族情報</label>
                            <input id="kakocho" type="radio" name="tab_item" {{ request('tab') === 'kakocho' ? 'checked' : '' }}>
                            <label class="tab_item" for="kakocho">過去帳</label>
                            <div class="tab_content" id="family_content">
                                <div class="tab_content_description">
                                <p class="c-txtsp">
                                    <button type="button">
                                        <a href="{{ route('follower.create', $danka->id )}}" class="btn-entry">
                                            <i class="fa-solid fa-plus"></i><span class="mx-2">家族情報登録</span>
                                        </a>
                                    </button>
                                    @if ($families->isNotEmpty())
                                        <table class="table_family">
                                            <thead>
                                                <tr>
                                                    <th class="name">氏名</th>
                                                    <th class="namekana">氏名かな</th>
                                                    <th class="relationship">続柄</th>
                                                    <th><span></span></th>
                                                </tr>
                                            </thead>
                                            @foreach ($families as $family)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('follower.show', $family->id )}}" class="blue_line">{{$family->name}}</a>
                                                    </td>
                                                    <td>{{$family->namekana}}</td>
                                                    <td>{{$family->relationship}}</td>
                                                    <td>
                                                        <div class="btn-center">
                                                            <a href="{{ route('danka.chiefmourner.change', ['danka_id' => $danka->id, 'id' => $family->id] )}}" class="btn-seshu"
                                                                onclick="return confirm('施主を交代します。よろしいですか？')">
                                                                <i class="fa-solid fa-exchange"></i><span class="mx-2">施主交代</span>
                                                            </a>
                                                            <a href="{{ route('kakocho.edit', $family->id) }}" class="btn-kakocho">
                                                                <i class="fa-solid fa-file"></i><span class="mx-2">過去帳</span>
                                                            </a>
                                                            <a href="{{ route('follower.edit', $family->id )}}" class="btn-edit">
                                                                <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                                            </a>
                                                            <form onsubmit="return deleteFamily();"
                                                                class="btn-delete"
                                                                action="{{ route('follower.destroy', ['danka_id' => $family->danka_id, 'id' => $family->id]) }}" method="post"
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
                                        </table>
                                    @else
                                        <div class="bu-blue-100 border-t border-b border-blue-500 texxt-blue-700 px-4 py-3" role="alert">
                                            <p class="font-bold">家族情報の登録がありません</p>
                                        </div>
                                    @endif
                                    <br>
                                    {{ $families->appends(['tab' => 'family'])->links() }}
                                </p>
                                </div>
                            </div>
                            <div class="tab_content" id="kakocho_content">
                                <div class="tab_content_description">
                                    <p class="c-txtsp">
                                        <button type="button">
                                            <a href="{{ route('kakocho.create', $danka->id) }}" class="btn-entry">
                                                <i class="fa-solid fa-plus"></i><span class="mx-2">過去帳登録</span>
                                            </a>
                                        </button>
                                        @if ($kakochos->isNotEmpty())
                                            <table class="table_kakocho">
                                                <thead>
                                                    <tr>
                                                        <th class="kaimyou">戒名</th>
                                                        <th class="zokumyou">俗名</th>
                                                        <th class="deathanniversary">命日</th>
                                                        <th><span></span></th>
                                                    </tr>
                                                </thead>
                                                @foreach ($kakochos as $kakocho)
                                                    <tr>
                                                        <td>{{ $kakocho->kaimyou }}</td>
                                                        <td>
                                                            <a href="{{ route('kakocho.show', $kakocho->id )}}" class="blue_line">{{ $kakocho->zokumyou }}<a>
                                                        </td>
                                                        <td>{{ AD_to_JA_conv_calender($kakocho->deathanniversary) }}</td>
                                                        <td>
                                                            <div class="btn-center">
                                                                <a href="{{ route('nenkilist.index', $kakocho->id )}}" class="btn-nenki">
                                                                    <i class="fa-solid fa-file"></i><span class="mx-2">年忌表</span>
                                                                </a>
                                                                <a href="{{ route('taiyalist.index', $kakocho->id )}}" class="btn-taiya">
                                                                    <i class="fa-solid fa-note-sticky"></i><span class="mx-2">逮夜表</span>
                                                                </a>

                                                                <a href="{{ route('kakocho.edit', $kakocho->id )}}" class="btn-edit">
                                                                    <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                                                                </a>
                                                                <form onsubmit="return deleteKakocho();"
                                                                    class="btn-delete"
                                                                    action="{{ route('kakocho.destroy', ['danka_id' => $kakocho->danka_id, 'id' => $kakocho->id]) }}" method="post"
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
                                            </table>
                                        @else
                                            <div class="bu-blue-100 border-t border-b border-blue-500 texxt-blue-700 px-4 py-3" role="alert">
                                                <p class="font-bold">過去帳の登録がありません</p>
                                            </div>
                                        @endif
                                        <br>
                                        {{ $kakochos->appends(['tab' => 'kakocho'])->links() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<script>
    function deleteFamily(){
        if(confirm('削除します。よろしいですか？')){
            return true;
        } else {
            return false;
        }
    }
</script>
<script>
    function deleteKakocho(){
        if(confirm('削除します。よろしいですか？')){
            return true;
        } else {
            return false;
        }
    }
</script>
</x-app-layout>