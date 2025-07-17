<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">ダッシュボード</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full mb-3">
        </div>
        <div class="flex flex-col justify-center items-center">
            <div class="w-full grid grid-cols-12 gap-x-3 gap-y-3">
                <div class="col-span-12">
                    <div class="article-head tracking-widest font-medium px-3 py-2"  style="border-left: 6px solid #718096;">
                        マスタ
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('generalmaster.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">汎用マスタ</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('templemaster.createOrEdit') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">自寺院マスタ</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('kaiki.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">回忌設定</a>
                </div>
                <div class="col-span-12 mt-8">
                    <div class="article-head tracking-widest font-medium px-3 py-2"  style="border-left: 6px solid #718096;">
                        檀信徒名簿
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('danka.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">檀家一覧</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('dankalist.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">一覧表印刷</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('postcard.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">はがき・封筒</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('kaimyoucheck.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">同名戒名検索</a>
                </div>
                <div class="col-span-12 sm:col-span-2"></div>
                <div class="col-span-12 sm:col-span-2"></div>
            </div>
        </div>
    </main>   
</x-app-layout>