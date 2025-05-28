<x-app-layout>

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/app.css">

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-4 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">一覧表印刷</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center">
            <hr class="w-full mb-3">
        </div>
        <div class="flex flex-col justify-center items-center">
            <div class="w-full grid grid-cols-12 gap-x-3 gap-y-3">
                <div class="col-span-12">
                    <div class="article-head tracking-widest font-medium px-3 py-2"  style="border-left: 6px solid #718096;">
                        名簿より
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('arealist.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">地区別名簿一覧表</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('tanagyouheader.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">棚経参一覧表</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('haruhiganheader.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">春彼岸一覧表</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('akihiganheader.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">秋彼岸一覧表</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('hanamatsurilist.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">花まつり一覧表</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('gozikailist.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">護持会名簿一覧表</a>
                </div>
                <div class="col-span-12 mt-8">
                    <div class="article-head tracking-widest font-medium px-3 py-2"  style="border-left: 6px solid #718096;">
                        過去帳より
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('hondoulist.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">本堂掲示用一覧表</a>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('hatsubonlist.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">初盆忌一覧表</a>
                </div>
                <div class="col-span-12 mt-8">
                    <div class="article-head tracking-widest font-medium px-3 py-2"  style="border-left: 6px solid #718096;">
                        その他
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-2">
                    <a href="{{ route('nenkailist.index') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">年回表</a>
                </div>
                <div class="col-span-12 sm:col-span-2 mb-8">
                    <a href="{{ route('paymentslip.createOrEdit') }}" class="text-center items-center border rounded-md text-base font-semibold uppercase tracking-widest text-gray-800 bg-white border-slate-500 hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring ring-gray-200 transition ease-in-out duration-150 shadow-sm py-4 w-full block">払込表</a>
                </div>
            </div>
        </div>
    </main>   
</x-app-layout>