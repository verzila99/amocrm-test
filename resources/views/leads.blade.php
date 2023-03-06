<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .table {
        width: 100%;
        margin-bottom: 20px;
        border: 15px solid #F2F8F8;
        border-top: 5px solid #F2F8F8;
        border-collapse: collapse;
    }

    .table th {
        font-weight: bold;
        padding: 5px;
        background: #F2F8F8;
        border: none;
        border-bottom: 5px solid #F2F8F8;
    }

    .table td {
        padding: 5px;
        border: none;
        border-bottom: 5px solid #F2F8F8;
    }
</style>

<body>
    @if ($leads)
    {{-- @dd($leads) --}}
    <table class="table">
        <thead>
            <tr>
                <th>ИД</th>
                <th>Имя</th>
                <th>Цена</th>
                <th>Ответственный</th>
                <th>Дата создания</th>
                <th>Дата обновления</th>
                <th>ИД воронки</th>
                <th>Контакты</th>
                <th>Компания</th>
                <th>Ближайшая задача</th>
                <th>Удалена</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leads as $lead)
            <tr>
                <td>{{$lead->id}}</td>
                <td>{{$lead->name}}</td>
                <td>{{$lead->price}}</td>
                <td>{{$lead->responsible_user_id}}</td>
                <td>{{ date('Y-m-d H:i:s',$lead->created_at)}}</td>
                <td>{{ date('Y-m-d H:i:s',$lead->updated_at)}}</td>
                <td>{{$lead->pipeline_id}}</td>
                <td>
                    @foreach (json_decode($lead->contacts) as $contact)
                    {{$contact->name}}<br />

                    @endforeach
                </td>
                <td>{{json_decode($lead->company)->name}}</td>
                <td>{{ date('Y-m-d H:i:s',$lead->closest_task_at)}}</td>
                {{-- <td>{{$lead->labor_cost}}</td> --}}
                <td>{{$lead->is_deleted ? 'Да' :'Нет'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <form method="POST" action="{{ route('store-leads')}}">
        @csrf
        <button type="submit">Обновить</button>
    </form>
</body>

</html>