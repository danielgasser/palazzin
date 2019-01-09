@extends('layout.master')
@section('content')
{{--Tools::dd($allBills)--}}
<div id="listBills" class="table-responsive">
    @if(sizeof($allBills) > 0)
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
            <td class="white-row" id="bill_name">{{link_to_asset('public/files/__clerk/' . $b->getFileName(), $b->getFileName(), array(/*'download' => $b->getFileName()*/))}}</td>
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
        <script src="{{asset('assets/js/libs/tablesorter/jquery.tablesorter.min.js')}}"></script>
    @stop

@stop
