# RoomApp - Reservation-System for families and small business
## Beschreibung
Es soll eine Online-Applikation erstellt werden, die es registrierten Benutzern erlaubt, ihre Palazzin-Reservationen
zu handhaben. Reservationen werden verrechnet. Dies ist ein 2-jähriger Pilotversuch.

## Allgemeine Feststellungen
Die App wird modular geplant und umgesetzt, damit eine möglichst grosse Flexibilität und Anpassungsfähigkeit
gewährleistet ist.
- Benutzer-Rollen & Rollen-Rechte können beliebig erweitert werden. Für den 2-jährigen Pilotversuch
werden Rollen bzw. Rechte bewusst schlank gehalten.
- Preise & Tarife sind jederzeit anpassbar und beeinflussen nicht den Ablauf der App.
- Die nachfolgenden Regeln sind für den Pilotversuch bestimmend und können erst nach Ablauf der 2
Jahre geändert werden. (Konzept-Änderungen)
- Benutzer können nur nach Ablauf der Mindestfrist von 2 Jahren gelöscht werden. Sie können während 2
Jahren auf inaktiv geschaltet, danach reaktiviert oder ganz gelöscht werden.

## Regeln
### Benutzermanagement

- Nur VR's und ADMIN's dürfen neue BB's erfassen, de/aktivieren oder löschen.
- Jeder BB erhält ein Login für palazzin.ch bestehend aus Benutzername vorname.nachname &
Passwort.
- Das Passwort kann jederzeit vom BB geändert werden oder bei Verlust neu erstellt werden.
- Daz- u muss der Benutzername, E-Mail-Adresse angegeben, sowie eine "Erinnerungsfrage" beantwortet
werden.
- Das Passwort muss mindestens 10 Zeichen lang sein und sowohl mindestens 1 Grossbuchstabe, 1
Zahl, sowie 1 Sonderzeichen beinhalten.
- Jeder BB ist für die Richtigkeit seiner Daten verantwortlich.
- Jeder BB muss eine gültige E-Mail-Adresse angeben.
- Jeder BB muss eine gültige Rechnungsadresse angeben.
- Jeder BB muss ein gültiges Zahlungsverfahren angeben.
- Jeder BB muss für 2 Jahre registriert und/oder aktiv bleiben.
- Ein BB kann bei folgenden Vorfällen gesperrt werden.
- Bei anhaltender Verzögerung oder Ausbleiben der Zahlungen
- Bei wiederholtem Tricksen der Anzahl/Art Gäste
- Bei massiver Vernachlässigung des Hauses
- AG's können ihre Aktien managen/bezahlen.
- Der VR setzt die Anzahl Aktien pro Aktionär fest. Eine Jahresrechnung wird automatisch verschickt.
- Jeder AG muss eine gültige Rechnungsadresse für die Aktienrechnung angeben.
### Benutzer
- 
- Nur BB's dürfen reservieren.
- Alle BB's dürfen alle Reservationen einsehen.
- Jeder BB kann Gäste zu seiner Reservation hinzufügen. (Gasteintrag) Siehe R ESERVATIONEN 1.4.4
- Jeder BB kann seine Reservationen bearbeiten, sofern der Aufenthalt noch nicht begonnen hat.
- Nach Beginn des Aufenthalts kann folgendes bearbeitet werden:
    - Abreisedatum der Reservation
    - Anzahl Zimmer
    - Gasteinträge: Abreisedatum des Gasteintrags.
- Alle BB's dürfen alle Palazziner kontaktieren.
- BB's können ihre Generation erfassen, um gezielte Events zu organisieren.
### Rollen
Es gibt verschiedene Benutzerrollen, die jeweils verschiedene Rechte auf der Website besitzen:
- ADMIN => Reine Administration. nur Daniel Gasser
- AG => Aktionär
- BB => Benutzungsberechtigter
- GG => Gast bis 6 Jahre --> Gratis
- GK => Gast 6-18 Jahre
- GJ => Gast 18-25 Jahre
- GE => Gast ab 25 Jahren
- GU => Übersee Palazziner

### Rollen-Rechte
Aus den Benutzerrollen ergeben sich Zugriffsrechte auf bestimmte Bereiche der Website. Da Gäste keinerlei
Zugriff auf unsere App haben werden, benötigen sie auch keine Rechte.

Ausgenommen sind GU's. Diese haben gleiche Rechte wie BB's, ausser, dass sie den GE-Tarif pro
Übernachtung bezahlen und keinen Jahresbeitrag leisten.

|  | ADMIN | Verwaltungsrat | STATS, Aktionäre, Jann | RES & GU, Benutzungsberechtigte(r) |
|---|:---:|:---:|:---:|:---:|
|Statistiken einsehen | X | X | X | X |
|Benutzer erfassen/löschen | X | X |  |  |
|Reservationen tätigen/bearbeiten | X |  |  | X* |
|Reservationen löschen | X |  |  | X* |
|Reservationen ansehen | X | X | X | X |
|Bezahlen |  |  |  | X |
|Preise & Tarife bearbeiten | X | X |  |  |
|Palazziner kontaktieren | X | X | X | X |
\* Nur bis zur Anreise des BB's, bzw. bis zur Anreise der Gäste 

### Statistiken

Alle Rollen dürfen alle Statistiken einsehen.

---
Das gesamte Pflichtenheft ist [hier] als PDF hinterlegt.
