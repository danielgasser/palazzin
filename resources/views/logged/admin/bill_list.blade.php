@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css')}}/datatables_roomapp_reservation.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/js/v3')}}/DataTables/datatables.min.css"/>

    <script type="text/javascript" src="{{asset('assets/js/v3')}}/DataTables/datatables.min.js"></script>

@stop
@section('content')
{{--Tools::dd($allBills)--}}
<div id="listBills" class="table-responsive">
    @if(sizeof($allBills) > 0)
    <table id="bill_all_totals">
        <thead>
            <tr>
                <th>Rechnung</th>
            </tr>
        </thead>
        <tbody>
        @foreach($allBills as $b)
        <tr>
            <td>
                <a href="{{asset('/files/__clerk/' . $b->getFileName())}}">{{$b->getFileName()}}</a>
        </tr>
            @endforeach
        </tbody>
    </table>
        @endif
</div>
    @section('scripts')
    @parent
    <script>
        var autid = '{{Auth::id()}}';
    </script>
        <script src="{{asset('assets/js/inits/bill_list_init.js')}}"></script>
    @stop

@stop
