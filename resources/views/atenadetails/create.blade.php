<x-app-layout>
    <link rel="stylesheet" href="/css/style.css" >
    <link rel="stylesheet" href="/css/app.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <main class="py-2 px-2 sm:px-4">
        <div class="flex flex-col justify-center items-center z-10"></div>
        <div class="mb-3 flex flex-col justify-center items-center">
            <div class="header-container">
                <div class="header-title">宛名 / 新規登録</div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center mb-4">
            <hr class="w-full mb-4">
        </div>
        <div class="registration">
            <form action="{{ route('atenadetail.store') }}" method="post">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-sm text-red-600 rounded-md p-4 mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <table class="registration-table">
                    <tbody>
                        <input type="hidden" name="atena_header_id" value="{{ $atena_header_id }}" />
                        <tr>
                            <th class="registration-item">氏名</th>
                            <td class="registration-body">
                                <input type="text" name="name" class="registration-input" placeholder="例：山田　太郎" value="{{ old('name') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">氏名かな</th>
                            <td class="registration-body">
                                <input type="text" name="namekana" class="registration-input" placeholder="例：やまだ　たろう" value="{{ old('namekana') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">敬称</th>
                            <td class="registration-body">
                                <select id ="keishou" name="keishou" class="registration-select">
                                    <option selected></option>
                                    @foreach(App\Consts\KeishouConsts::KEISHOU_LIST as $name => $number)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">郵便番号</th>
                            <td class="registration-body">
                                <input type="text" name="postcode" class="registration-input" placeholder="例：123-4567" value="{{ old('postcode') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">住所１</th>
                            <td class="registration-body">
                                <input type="text" name="address1" class="registration-input" placeholder="例：〇〇県〇〇市〇〇町" value="{{ old('address1') }}" />
                            </td>
                        </tr>
                        <tr>
                            <th class="registration-item">住所２</th>
                            <td class="registration-body">
                                <input type="text" name="address2" class="registration-input" placeholder="例：〇〇丁目〇〇番地〇〇号" value="{{ old('address2') }}" />
                            </td>
                        </tr> 
                        <tr>
                            <th class="registration-item">はがき</th>
                            <td class="registration-body">
                                <select id="postcard" name="postcard" class="registration-select">
                                    @foreach(App\Consts\PostcardConsts::POSTCARD_LIST as $name => $number)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-btn">
                    <a class="form-btn2 form-inline-block" onclick="history.back()">
                        <i class="fa-regular fa-circle-left"></i><span class="mx-2">戻る</span>
                    </a>
                    <button type="submit" class="form-btn1 form-inline-block" onclick="return confirm('登録します。よろしいですか？')">
                        <i class="fa-solid fa-file-arrow-down"></i><span class="mx-2">登録</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
    
</x-app-layout>