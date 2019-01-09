<div id="old_ie" role="dialog" aria-labelledby="old_ie" aria-hidden="true" class="modal fade" style="display: block">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-warning">
      <div class="modal-header panel-heading">
        <h4 class="modal-title">{{trans('dialog.warning')}}</h4>
      </div>
      <div class="modal-body">
          <div style="font-size: 140%">
              <p id="message">Diese Webseite läuft <b>ausschliesslich</b> auf modernen Browsern.<br>
                  Empfohlen wird die neueste Version folgender Browser:
              <p>Fürs Desktop:
              <ul style="list-style-type: none;padding: 0">
                  <li><a href="https://www.google.ch/intl/de/chrome/browser/desktop/index.html" target="_blank">CHROME</a></li>
                  <li><a href="https://www.mozilla.org/de/firefox/new/" target="_blank">FIREFOX</a></li>
                  <li><a href="http://www.opera.com/de" target="_blank">OPERA</a></li>
                  <li><a href="http://windows.microsoft.com/de-CH/internet-explorer/download-ie" target="_blank">Internet Explorer von Windows 8 oder höher</a></li>
                  <hr>
                  <li>Oder der Browser auf Deinem mobilen Gerät<br>(IPad, IPhone, Samsung)</li>
              </ul>
              </p>
              <br>Besorg Dir einen solchen Browser, um weiter zu kommen!</p>
          </div>
          <p style="font-size: 100%">Dein Browser: {{$_SERVER['HTTP_USER_AGENT']}}</p>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
