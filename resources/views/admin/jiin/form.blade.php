<div class="my-3">
    <label for="name" class="">寺院名<span class="mx-2 required-mark">必須</span></label>
    <div>
        {!! Form::text('name', null, ['id' => 'name', 'class' => 'w-full lg:w-1/2', 'placeholder' => ""]) !!}
    </div>
</div>
<div class="my-3">
    <label for="email" class="">備考</label>
    <div>
        {!! Form::email('memo', null, ['id' => 'memo', 'class' => 'w-full lg:w-1/2', 'placeholder' => ""]) !!}
    </div>
</div>
