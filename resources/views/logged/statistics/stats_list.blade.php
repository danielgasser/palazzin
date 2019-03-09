@extends('layout.master')
@section('content')
    <div id="menu_stats">
        <h1>{{trans('admin.stats_chron.title')}}</h1>
        {{-- @include('layout.stats_menu')--}}
    </div>

{{--Tools::dd($allBills)--}}
<div id="listBills" class="table-responsive">
    <table id="bill_all_totals">
        <thead>
            <tr>
                <!--th class="white-row">Download</th>
                <th class="white-row">Anzeigen</th-->
            </tr>
        </thead>
        <tbody>
        @foreach($allBills as $b)
        <tr>
            <!--td class="white-row" id="billpath"><button class="btn btn-default">Download</button> </td-->
            <td class="white-row" id="bill_name"><a download="filename" target="_blank" href="{{$b['link']}}">{{$b['name']}}</a></td>
        </tr>
            @endforeach
        </tbody>
    </table>
</div>
    @section('scripts')
    @parent
    <script>
        var autid = '{{Auth::id()}}';
    </script>
    @stop

@stop
