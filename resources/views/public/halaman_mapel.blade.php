@extends('templates.public_templates')

@section('content')
    <table>
        <tr>
            @foreach ($data_mapel as $a)
                <td style="padding: 10px;"><button class="btn-primary">{{ $a->nama_mapel }}</button></td>
            @endforeach
        </tr>
    </table>
@endsection
