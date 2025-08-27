<!DOCTYPE html>
<html lang="ja">
<style>
    body {
        background-color: #fffacd;
        font-family: monospace, Serif;
        font-size: 14px;
    }
    h1 {
        font-size: 16px;
        color: #ff6666;
    }
</style>
<body>
<h1>システム管理者を登録しました</h1>
<p>お　名　前　：　{{$name}}</p>
<p>Ｅメール　　：　{{$mail}}</p>
<p>パスワード　：　{{$pass}}</p>
<br>
<a href="{{ url(route('admin.login'))}}">ログインページはこちら</a>
</body>
</html>
