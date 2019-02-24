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
    <p><b>Bitte wähle das/die gewünschte(n) Jahr(e) und klicke auf "Statistiken laden"</b></p>
    @for($i = $start; $i <= ($start + $duration); $i++)
        @if($i > intval($today->format('Y')))
            @php
            $disabled = 'disabled'
            @endphp
        @endif
        <div class="col-sm-1"><label style="color: {{$yearColors[$j]}}">{{$i}}</label><input {{$disabled}} name="year[]" data-toggle="toggle" data-on="<b>{{$i}}</b>" data-off="Aus" type="checkbox" value="{{$i}}" /></div>
        <?php $j++;
        ?>
    @endfor
        <div class="col-sm-1">
            <label>&nbsp;</label><button id="getYears" class="btn btn-default">Statistiken laden</button>
        </div>
</div>
