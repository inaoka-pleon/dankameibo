<x-app-layout>

    <x-slot name="header">
        <h2 class="header-inner header-nav">はがき</h2>
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
                            <a class="btn-default text-xs reset" href="{{ route('atenaheader.index') }}">宛名印刷</a>
                            <a class="btn-default text-xs reset" href="{{ route('generalpostcard.index') }}">はがき裏面作成</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>