<link rel="stylesheet" href="/css/style.css" >
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<div class="table-responsive">
    <table class="table-danka radius-table">
        <thead>
            <tr>
                <th class="general_name">名称</th>
                <th class="general_amount">金額</th>
                <th class="general_other"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($general_masters as $general_master)
                <tr>
                    <td class="visible-lg visible-md visible-sm">{{ $general_master->value1 }}</td>
                    <td class="visible-lg visible-md visible-sm column2">{{ $general_master->value2 }}</td>
                    <td class="center">
                        <a class="btn-edit" role="group" href="{{ route('generalmaster.edit', $general_master->id) }}">
                            <i class="fa-solid fa-edit"></i><span class="mx-2">編集</span>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br>
<div class="d-flex justify-content-center">
    {!! $general_masters->withQueryString()->appends(['k_sel_master' => @(Request::get('k_sel_master'))])->links('pagination::bootstrap-5') !!}
</div>
