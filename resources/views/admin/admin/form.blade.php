<div class="my-3">
    <label for="name" class="">ユーザー名<span class="mx-2 required-mark">必須</span></label>
    <div>
        {!! Form::text('name', null, ['id' => 'name', 'class' => 'w-full lg:w-1/2', 'placeholder' => "２０文字以内で入力してください"]) !!}
    </div>
</div>
<div class="my-3">
    <label for="email" class="">メール<span class="mx-2 required-mark">必須</span></label>
    <div>
        {!! Form::email('email', null, ['id' => 'email', 'class' => 'w-full lg:w-1/2', 'placeholder' => "sample@sample.com"]) !!}
    </div>
</div>
