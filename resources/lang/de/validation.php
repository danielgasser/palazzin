<?php

return array(

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

	'accepted'             => 'Das <span class="error-field">:attribute</span> muss akkzeptiert werden',
	'active_url'           => '<span class="error-field">:attribute</span> ist keine gültige URL',
	'after'                => '<span class="error-field">:attribute</span> muss nach :date sein',
	'alpha'                => '<span class="error-field">:attribute</span> darf nur Buchstaben enthalten',
	'alpha_dash'           => '<span class="error-field">:attribute</span> darf nur Buchstaben, Zahlen und Gednakenstriche (-) enthalten',
	'alpha_num'            => '<span class="error-field">:attribute</span> nur Buchstaben, Zahlen enthalten',
	'array'                => '<span class="error-field">:attribute</span> muss ein Feld sein',
	'before'               => '<span class="error-field">:attribute</span> muss vor :date sein',
	'between'              => array(
		'numeric' => '<span class="error-field">:attribute</span> muss zwischen :min und :max sein',
		'file'    => '<span class="error-field">:attribute</span> muss zwischen :min und :max Kilobytes sein',
		'string'  => '<span class="error-field">:attribute</span> muss zwischen :min und :max Zeichen lang sein',
		'array'   => '<span class="error-field">:attribute</span> muss zwischen :min und :max Teile enthalten',
	),
	'confirmed'            => '<span class="error-field">:attribute</span> Bestätigung stimmt nicht überein',
	'date'                 => '<span class="error-field">:attribute</span> ist kein gültiges Datum',
	'date_format'          => '<span class="error-field">:attribute</span> stimmt nicht mit dem Format :format überein',
	'different'            => '<span class="error-field">:attribute</span> und :other müssen verschieden sein',
	'digits'               => '<span class="error-field">:attribute</span> muss eine :digits Ziffer sein',
	'digits_between'       => '<span class="error-field">:attribute</span> muss zwischen :min und :max Ziffern sein',
	'email'                => '<span class="error-field">:attribute</span> muss eine gültige E-Mail Adresse sein',
	'exists'               => 'Das gewählte <span class="error-field">:attribute</span> muss existiert nicht',
	'empty'               => '<span class="error-field">:attribute</span> ist erforderlich',
	'image'                => '<span class="error-field">:attribute</span> muss ein Bild sein',
	'in'                   => 'Das gewählte <span class="error-field">:attribute</span> ist ungültig',
	'integer'              => '<span class="error-field">:attribute</span> muss eine ganze Zahl sein',
	'ip'                   => '<span class="error-field">:attribute</span> muss eine gültige IP Adresse sein',
	'max'                  => array(
		'numeric' => '<span class="error-field">:attribute</span> darf nicht grösser sein als :max',
		'file'    => '<span class="error-field">:attribute</span> darf nicht grösser sein als :max Kilobytes',
		'string'  => '<span class="error-field">:attribute</span> darf nicht grösser sein als :max Zeichen',
		'array'   => '<span class="error-field">:attribute</span> darf nicht grösser sein als :max Teile',
	),
	'mimes'                => '<span class="error-field">:attribute</span> muss eine Datei vom Typ :values sein',
	'min'                  => array(
		'numeric' => '<span class="error-field">:attribute</span> muss mindestens :min sein',
		'file'    => '<span class="error-field">:attribute</span> muss mindestens :min Kilobytes sein',
		'string'  => '<span class="error-field">:attribute</span> muss mindestens :min Zeichen haben',
		'array'   => '<span class="error-field">:attribute</span> muss mindestens :min Teile haben',
	),
	'not_in'               => 'gewähltes <span class="error-field">:attribute</span> ist ungültig',
	'numeric'              => 'Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> muss eine Zahl sein',
	'regex'                => 'Das Format des Felds <span class="error-field"><span class="error-field">:attribute</span></span> ist ungültig',
	'required'             => 'Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> ist erforderlich',
	'required_if'          => 'Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> ist erforderlich, wenn :other :value ist',
	'required_with'        => 'Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> ist erforderlich, wenn :values vorhanden ist',
	'required_with_all'    => 'Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> ist erforderlich, wenn :values vorhanden ist',
	'required_without'     => 'Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> ist erforderlich, wenn :values nicht vorhanden ist',
	'required_without_all' => 'Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> ist erforderlich, wenn keine :values vorhanden sind',
	'same'                 => 'Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> und das Feld :other müssen übereinstimmen',
	'size'                 => array(
		'numeric' => '<span class="error-field">:attribute</span> muss :size gross sein',
		'file'    => '<span class="error-field">:attribute</span> muss :size Kilobytes gross sein',
		'string'  => '<span class="error-field">:attribute</span> muss :size Zeichen lang sein',
		'array'   => '<span class="error-field">:attribute</span> muss :size Teile haben',
	),
	'unique'               => '<span class="error-field">:attribute</span> wurde schon gewählt',
	'url'                  => '<span class="error-field">:attribute</span> Format ist ungültig',
    'no_data' => array(
        'login_stats' => 'Keine Daten für den Zeitraum vom :start bis :end vorhanden'
    ),

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

	'custom' => array(
		'attribute-name' => array(
			'rule-name' => 'custom-message',
		),
	),

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

	'attributes' => array(
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
        'new_pass_confirmation' => 'Neues Passwort',
        'clan_id' => 'Stamm',
        'role_id_add' => 'Rolle',
        'role_tax_annual' => 'Jahresbeitrag',
        'role_tax_night' => 'Preis pro Nacht',
        'role_tax_stock' => 'Preis pro Aktie',
        'reservation_guest_started_at' => 'Anreisedatum',
        'reservation_guest_ended_at' => 'Abreisedatum',
        'reservation_guest_guests' => 'Art des Gastes',
        'reservation_guest_num' => 'Anzahl Gäste',
        'sum_guests' => 'Anzahl Betten',
        'reservation_nights' => 'Anzahl Nächte',
		'post_text' => 'Beitrag',
		'comment_text' => 'Kommentar'
    ),

);
