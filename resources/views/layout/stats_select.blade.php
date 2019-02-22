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
$j = 0;
?><div class="stats_select row">
    <p><b>Bitte wähle das/die gewünschte(n) Jahr(e) und klicke auf "Laden"</b></p>
    @for($i = $start; $i <= ($start + $duration); $i++)
        <div class="col-sm-1"><span class="label" style="color: {{$yearColors[$j]}}">{{$i}}</span><input name="year[]" data-toggle="toggle" type="checkbox" value="{{$i}}" /></div>
        <?php $j++;
        ?>
    @endfor
        <div class="col-sm-1">
            <span class="label">&nbsp;</span>  <button id="getYears" class="btn btn-default">Laden</button>
        </div>
        <div style="float: right; width: 107px; margin: 10px 0;"><br>
            <button style="display: none" id="asPDF" data-direction="L" data-font="18" class="btn btn-default">als PDF speichern</button>
        </div>
</div>
