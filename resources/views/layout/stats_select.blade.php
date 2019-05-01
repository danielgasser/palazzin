<?php
/**
 * Created by daniel.
 * User: daniel
 * Date: 2/19/16
 * Time: 10:06 AM
 * Project: roomapp
 * File: stats_select.blade.php
 */
$settings = Setting::getStaticSettings();
$startYear = new DateTime($settings['setting_calendar_start']);
$start = intval($startYear->format('Y'));
$duration = intval($settings['setting_calendar_duration']);
$today = new DateTime();
$disabled = '';
$j = 0;
?><div class="stats_select row">
    <p><b>Bitte wähle die gewünschten Jahre und klicke auf "Statistiken laden"</b></p>
    @for($i = $start; $i <= ($start + $duration); $i++)
        {{--@if($i <= intval($today->format('Y')))--}}
            <div class="col-sm-3"><label style="color: {{$yearColors[$j]}}; margin-right: 1em; font-size: 17px;">{{$i}}</label><input name="year[]" data-toggle="toggle" data-on="<b>{{$i}}</b>" data-off="Aus" type="checkbox" value="{{$i}}" /></div>
        {{--@endif--}}
        <?php $j++;
        ?>
    @endfor
        <div class="col-sm-3">
            <label>&nbsp;</label><button id="getYears" class="btn btn-default">Statistiken laden</button>
        </div>
</div>
