@extends('layout.master')
@section('header')
    @parent
    <link rel="stylesheet" type="text/css" href="{{asset('libs')}}/DataTables/datatables.min.css"/>

    <style>
        .dataTables_wrapper {
            margin: 0;
            border: none;
        }
        .form-control {
            width: auto;
        }
    </style>
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
            <td data-sort="{{$b->sortNumber}}">
                <a href="{{asset('/files/__clerk/' . $b->getFileName())}}">{{$b->getFileName()}}</a>
        </tr>
            @endforeach
        </tbody>
    </table>
        @endif
</div>
    @section('scripts')
    @parent
    <script type="text/javascript" src="{{asset('libs')}}/DataTables/datatables.min.js"></script>
    <script>
        var autid = '{{Auth::id()}}';
    </script>
        <script src="{{asset('js/bill_list_init.min.js')}}"></script>
    @stop

@stop
