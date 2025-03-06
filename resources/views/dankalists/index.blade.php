<x-app-layout>

    <x-slot name="header">
        <h2 class="header-inner header-nav">一覧表印刷</h2>
        <div class="flex flex-row-reverse">
        </div>
    </x-slot>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900"> 
                    <div class="hs-accordion-group" data-hs-accordion-always-open="">
                        <div class="hs-accordion active" id="hs-basic-always-open-heading-one">
                            <a class="btn-default text-xs reset" href="{{ route('arealist.index') }}">地区別名簿一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('tanagyouheader.index') }}">棚経参一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('haruhiganheader.index') }}">春彼岸一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('akihiganheader.index') }}">秋彼岸一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('hanamatsurilist.index') }}">花まつり一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('gozikailist.index') }}">護持会名簿一覧表</a>
                            <!-- <a class="btn-default text-xs reset" href="{{ route('gozikaikaihilist.index') }}">護持会会費</a> -->
                            <!-- <a class="btn-default text-xs reset" href="{{ route('kaihilist.index') }}">会費年度別一覧</a> -->

                        </div>
                        <br>
                        <div class="hs-accordion active" id="hs-basic-always-open-heading-one">
                            <!-- <a class="btn-default text-xs reset" href="{{ route('areakaikilist.index') }}">地区別回忌一覧表</a> -->
                            <!-- <a class="btn-default text-xs reset" href="{{ route('kaikireibolist.index') }}">回忌別霊簿一覧表</a> -->
                            <!-- <a class="btn-default text-xs reset" href="{{ route('kakuiereibolist.index') }}">檀信徒各家霊簿一覧表</a> -->
                            <!-- <a class="btn-default text-xs reset" href="{{ route('nendoreibolist.index') }}">年度別霊簿一覧表</a> -->
                            <a class="btn-default text-xs reset" href="{{ route('hondoulist.index') }}">本堂掲示用一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('hatsubonlist.index') }}">初盆忌一覧表</a>
                        </div>
                        <br>
                        <div class="hs-accordion active" id="hs-basic-always-open-heading-one">
                            <a class="btn-default text-xs reset" href="{{ route('nenkailist.index') }}">年回表</a>
                            <a class="btn-default text-xs reset" href="{{ route('paymentslip.createOrEdit') }}">払込票</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>