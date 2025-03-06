<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">

    <!-- エラーの表示を追加 -->
    @include('errors.form_errors')
    
    <main class="mt-1 py-4 px-2 sm:px-4">
        <div class="flex  flex-col justify-center items-center z-10"></div>
        <div class="flex flex-col justify-center items-center">
            <div class="flex justify-between bg-white border-l-8 border-b-2 border-slate-500 shadow-sm py-3 sm:py-4 px-4 sm:px-4 rounded-bl w-full max-w-7xl">
                <div class="text-gray-800 text-xl font-semibold">寺院情報詳細</div>
                <div class="inline-flex -mt-1">
                    <a href="{{ route('temple.show', $member->temple_id) }}">
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
                                <th>氏名</th>
                                <td>{{ $member->name }}</td>
                            </tr>
                            <tr>
                                <th>氏名かな</th>
                                <td>{{ $member->namekana }}</td>
                            </tr>
                            <tr>
                                <th>敬称</th>
                                <td>{{ $member->title }}</td>
                            </tr>
                            <tr>
                                <th>脇敬称</th>
                                <td>{{ $member->subtitle }}</td>
                            </tr>

                            <tr>
                                <th>師</th>
                                <td>{{ $member->teacher }}</td>
                            </tr>  
                            <tr>  
                                <th>資格</th>
                                <td>{{ $member->qualification }}</td>
                            </tr>
                            <tr>
                                <th>入会名</th>
                                <td>{{ $member->nyuukai_name }}</td>
                            </tr>
                            <tr>
                                <th>郵便番号</th>
                                <td>{{ $member->postcode }}</td>
                            </tr>
                            <tr>
                                <th>住所１</th>
                                <td>{{ $member->address1 }}</td>
                            </tr>
                            <tr>
                                <th>住所２</th>
                                <td>{{ $member->address2 }}</td>
                            </tr>
                            <tr>
                                <th>電話番号</th>
                                <td>{{ $member->tel }}</td>
                            </tr>
                            <tr>
                                <th>FAX</th>
                                <td>{{ $member->fax }}</td>
                            </tr>
                            <tr>
                                <th>手紙区分</th>
                                <td>{{ $member->letterdivision }}</td>
                            </tr>
                            <tr>
                                <th>年賀状区分</th>
                                <td>{{ $member->newyearscarddivision }}</td>
                            </tr>
                            <tr>
                                <th>暑中見舞区分</th>
                                <td>{{ $member->summergreetingdivision }}</td>
                            </tr>
                            <tr>
                                <th>備考</th>
                                <td>{{ $member->memo }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>