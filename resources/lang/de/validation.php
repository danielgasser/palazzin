<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class Some of these rules have multiple versions such
	| as the size rules Feel free to tweak each of these messages here
	|
	*/
    'accepted'             => 'Das :attribute muss akkzeptiert werden',
    'active_url'           => ':attribute ist keine gültige URL',
    'after'                => ':attribute muss nach :date sein',
    'alpha'                => ':attribute darf nur Buchstaben enthalten',
    'alpha_dash'           => ':attribute darf nur Buchstaben, Zahlen und Gedankenstriche (-] enthalten',
    'alpha_num'            => ':attribute nur Buchstaben, Zahlen enthalten',
    'array'                => ':attribute muss ein Feld sein',
    'before'               => ':attribute muss vor :date sein',
    'before_or_equal' => ':attribute muss gleich oder vor :date sein',
    'after_or_equal' => ':attribute muss gleich oder nach :date sein',
    'between'              => [
        'numeric' => ':attribute muss zwischen :min und :max sein',
        'file'    => ':attribute muss zwischen :min und :max Kilobytes sein',
        'string'  => ':attribute muss zwischen :min und :max Zeichen lang sein',
        'array'   => ':attribute muss zwischen :min und :max Teile enthalten',
    ],
    'confirmed'            => ':attribute Bestätigung stimmt nicht überein',
    'date'                 => ':attribute ist kein gültiges Datum',
    'date_format'          => ':attribute stimmt nicht mit dem Format :format überein',
    'different'            => ':attribute und :other müssen verschieden sein',
    'digits'               => ':attribute muss eine :digits Ziffer sein',
    'digits_between'       => ':attribute muss zwischen :min und :max Ziffern sein',
    'email'                => ':attribute muss eine gültige E-Mail Adresse sein',
    'exists'               => 'Das gewählte :attribute muss existiert nicht',
    'empty'               => ':attribute ist erforderlich',
    'image'                => ':attribute muss ein Bild sein',
    'in'                   => 'Das gewählte :attribute ist ungültig',
    'integer'              => ':attribute muss eine ganze Zahl sein',
    'ip'                   => ':attribute muss eine gültige IP Adresse sein',
    'max'                  => [
        'numeric' => ':attribute darf nicht grösser sein als :max',
        'file'    => ':attribute darf nicht grösser sein als :max Kilobytes',
        'string'  => ':attribute darf nicht grösser sein als :max Zeichen',
        'array'   => ':attribute darf nicht grösser sein als :max Teile',
    ],
    'mimes'                => ':attribute muss eine Datei vom Typ :values sein',
    'min'                  => [
        'numeric' => ':attribute muss mindestens :min sein',
        'file'    => ':attribute muss mindestens :min Kilobytes sein',
        'string'  => ':attribute muss mindestens :min Zeichen haben',
        'array'   => ':attribute muss mindestens :min Teile haben',
    ],
    'not_in'               => 'gewähltes :attribute ist ungültig',
    'numeric'              => 'Das Feld <span class="error-field">:attribute</span> muss eine Zahl sein',
    'regex'                => 'Das Format des Felds <span class="error-field">:attribute</span> ist ungültig',
    'required'             => 'Das Feld <span class="error-field">:attribute</span> ist erforderlich',
    'required_if'          => 'Das Feld <span class="error-field">:attribute</span> ist erforderlich, wenn :other :value ist',
    'required_with'        => 'Das Feld <span class="error-field">:attribute</span> ist erforderlich, wenn :values vorhanden ist',
    'required_with_all'    => 'Das Feld <span class="error-field">:attribute</span> ist erforderlich, wenn :values vorhanden ist',
    'required_without'     => 'Das Feld <span class="error-field">:attribute</span> ist erforderlich, wenn :values nicht vorhanden ist',
    'required_without_all' => 'Das Feld <span class="error-field">:attribute</span> ist erforderlich, wenn keine :values vorhanden sind',
    'same'                 => 'Das Feld <span class="error-field">:attribute</span> und das Feld :other müssen übereinstimmen',
    'size'                 => [
        'numeric' => ':attribute muss :size gross sein',
        'file'    => ':attribute muss :size Kilobytes gross sein',
        'string'  => ':attribute muss :size Zeichen lang sein',
        'array'   => ':attribute muss :size Teile haben',
    ],
    'unique'               => ':attribute wurde schon gewählt',
    'url'                  => ':attribute Format ist ungültig',
    'no_data' => [
        'login_stats' => 'Keine Daten für den Zeitraum vom :start bis :end vorhanden'
    ],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention 'attributerule' to name the lines This makes it quick to
	| specify a specific custom language line for a given attribute rule
	|
	*/

	'custom' => [
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of 'email' This simply helps us make messages a little cleaner
	|
	*/

	'attributes' => [
        'user_first_name' => 'Vorname',
        'user_name' => 'Name',
        'user_family' => 'Halb-Stamm',
        'user_login_name' => 'Benutzername',
        'email' => 'E-Mail',
        'user_address' => 'Adresse',
        'user_city' => 'Ort',
        'user_zip' => 'PLZ',
        'user_country_code' => 'Land',
        'user_fon1' => 'N°1',
        'user_fon1_label' => 'Art der Nummer °1',
        'user_birthday' => 'Geburtstag',
        'user_avatar' => 'Bild',
        'user_answer' => 'Sicherheitsantwort',
        'user_question' => 'Sicherheitsfrage',
        'user_payment_method' => 'Zahlungsmethode',
        'password' => 'Passwort',
        'new_pass' => 'Neues Passwort',
        'new_pass_confirmation' => 'Neues Passwort bestätigen',
        'clan_id' => 'Stamm',
        'role_id_add' => 'Rolle',
        'role_tax_annual' => 'Jahresbeitrag',
        'role_tax_night' => 'Preis pro Nacht',
        'role_tax_stock' => 'Preis pro Aktie',
        'reservation_started_at' => 'Anreisedatum',
        'reservation_guest_started_at.*' => 'Anreisedatum Gast',
        'reservation_guest_ended_at.*' => 'Abreisedatum Gast',
        'reservation_guest_price.*' => 'Preis pro Nacht',
        'reservation_guest_num.*' => 'Anzahl Gäste',
        'reservation_ended_at' => 'Abreisedatum',
        'reservation_guest_guests' => 'Art des Gastes',
        'reservation_guest_num' => 'Anzahl Gäste',
        'sum_guests' => 'Anzahl Betten',
        'reservation_nights' => 'Anzahl Nächte',
		'post_text' => 'Beitrag',
		'comment_text' => 'Kommentar'
    ],

];
