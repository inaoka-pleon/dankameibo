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
                            <a class="btn-default text-xs reset" href="{{ route('templelist.index') }}">寺院名簿一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('memberlist.index') }}">会員名簿一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('templelist.index') }}">寺院謝誼弔用一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('templelist.index') }}">寺院謝誼祝用一覧表</a>
                            <a class="btn-default text-xs reset" href="{{ route('templelist.index') }}">簡易謝誼袋印刷</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>