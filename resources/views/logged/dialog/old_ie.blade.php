<div id="old_ie" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title-warning">{!! trans('dialog.warning') !!}</h4>
            </div>
            <div class="modal-body">
                <p id="message">Diese Webseite läuft <b>ausschliesslich</b> auf modernen Browsern.<br>
                    Empfohlen wird die neueste Version folgender Browser:</p>
                <p>Fürs Desktop:
                <ul style="list-style-type: none;padding: 0">
                    <li><a href="https://www.google.ch/intl/de/chrome/browser/desktop/index.html" target="_blank">CHROME</a></li>
                    <li><a href="https://www.mozilla.org/de/firefox/new/" target="_blank">FIREFOX</a></li>
                    <li><a href="http://www.opera.com/de" target="_blank">OPERA</a></li>
                    <li><a href="http://windows.microsoft.com/de-CH/internet-explorer/download-ie" target="_blank">Internet Explorer
                            von Windows 8 oder höher</a></li>
                    <hr>
                    <li>Oder der Browser auf Deinem mobilen Gerät<br>(IPad, IPhone, Samsung)</li>
                </ul>
                </p>
                <p>Besorg Dir einen solchen Browser, um weiter zu kommen!</p>
                <hr>
                <p style="font-size: 100%">Dein Browser: {{$_SERVER['HTTP_USER_AGENT']}}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default btn-dialog-left">{!!trans('dialog.ok')!!}</button>
            </div>
        </div>
    </div>
</div>
