<?php
/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 05.10.14
 * Time: 04:23
 */

class Tools {

    /**
     * @var array currencies
     *
     */
    public static $currencies = array (
        'ALL' => 'Albania Lek',
        'AFN' => 'Afghanistan Afghani',
        'ARS' => 'Argentina Peso',
        'AWG' => 'Aruba Guilder',
        'AUD' => 'Australia Dollar',
        'AZN' => 'Azerbaijan New Manat',
        'BSD' => 'Bahamas Dollar',
        'BBD' => 'Barbados Dollar',
        'BDT' => 'Bangladeshi taka',
        'BYR' => 'Belarus Ruble',
        'BZD' => 'Belize Dollar',
        'BMD' => 'Bermuda Dollar',
        'BOB' => 'Bolivia Boliviano',
        'BAM' => 'Bosnia and Herzegovina Convertible Marka',
        'BWP' => 'Botswana Pula',
        'BGN' => 'Bulgaria Lev',
        'BRL' => 'Brazil Real',
        'BND' => 'Brunei Darussalam Dollar',
        'KHR' => 'Cambodia Riel',
        'CAD' => 'Canada Dollar',
        'KYD' => 'Cayman Islands Dollar',
        'CLP' => 'Chile Peso',
        'CNY' => 'China Yuan Renminbi',
        'COP' => 'Colombia Peso',
        'CRC' => 'Costa Rica Colon',
        'HRK' => 'Croatia Kuna',
        'CUP' => 'Cuba Peso',
        'CZK' => 'Czech Republic Koruna',
        'DKK' => 'Denmark Krone',
        'DOP' => 'Dominican Republic Peso',
        'XCD' => 'East Caribbean Dollar',
        'EGP' => 'Egypt Pound',
        'SVC' => 'El Salvador Colon',
        'EEK' => 'Estonia Kroon',
        'EUR' => 'Euro Member Countries',
        'FKP' => 'Falkland Islands (Malvinas) Pound',
        'FJD' => 'Fiji Dollar',
        'GHC' => 'Ghana Cedis',
        'GIP' => 'Gibraltar Pound',
        'GTQ' => 'Guatemala Quetzal',
        'GGP' => 'Guernsey Pound',
        'GYD' => 'Guyana Dollar',
        'HNL' => 'Honduras Lempira',
        'HKD' => 'Hong Kong Dollar',
        'HUF' => 'Hungary Forint',
        'ISK' => 'Iceland Krona',
        'INR' => 'India Rupee',
        'IDR' => 'Indonesia Rupiah',
        'IRR' => 'Iran Rial',
        'IMP' => 'Isle of Man Pound',
        'ILS' => 'Israel Shekel',
        'JMD' => 'Jamaica Dollar',
        'JPY' => 'Japan Yen',
        'JEP' => 'Jersey Pound',
        'KZT' => 'Kazakhstan Tenge',
        'KPW' => 'Korea (North) Won',
        'KRW' => 'Korea (South) Won',
        'KGS' => 'Kyrgyzstan Som',
        'LAK' => 'Laos Kip',
        'LVL' => 'Latvia Lat',
        'LBP' => 'Lebanon Pound',
        'LRD' => 'Liberia Dollar',
        'LTL' => 'Lithuania Litas',
        'MKD' => 'Macedonia Denar',
        'MYR' => 'Malaysia Ringgit',
        'MUR' => 'Mauritius Rupee',
        'MXN' => 'Mexico Peso',
        'MNT' => 'Mongolia Tughrik',
        'MZN' => 'Mozambique Metical',
        'NAD' => 'Namibia Dollar',
        'NPR' => 'Nepal Rupee',
        'ANG' => 'Netherlands Antilles Guilder',
        'NZD' => 'New Zealand Dollar',
        'NIO' => 'Nicaragua Cordoba',
        'NGN' => 'Nigeria Naira',
        'NOK' => 'Norway Krone',
        'OMR' => 'Oman Rial',
        'PKR' => 'Pakistan Rupee',
        'PAB' => 'Panama Balboa',
        'PYG' => 'Paraguay Guarani',
        'PEN' => 'Peru Nuevo Sol',
        'PHP' => 'Philippines Peso',
        'PLN' => 'Poland Zloty',
        'QAR' => 'Qatar Riyal',
        'RON' => 'Romania New Leu',
        'RUB' => 'Russia Ruble',
        'SHP' => 'Saint Helena Pound',
        'SAR' => 'Saudi Arabia Riyal',
        'RSD' => 'Serbia Dinar',
        'SCR' => 'Seychelles Rupee',
        'SGD' => 'Singapore Dollar',
        'SBD' => 'Solomon Islands Dollar',
        'SOS' => 'Somalia Shilling',
        'ZAR' => 'South Africa Rand',
        'LKR' => 'Sri Lanka Rupee',
        'SEK' => 'Sweden Krona',
        'CHF' => 'Switzerland Franc',
        'SRD' => 'Suriname Dollar',
        'SYP' => 'Syria Pound',
        'TWD' => 'Taiwan New Dollar',
        'THB' => 'Thailand Baht',
        'TTD' => 'Trinidad and Tobago Dollar',
        'TRY' => 'Turkey Lira',
        'TRL' => 'Turkey Lira',
        'TVD' => 'Tuvalu Dollar',
        'UAH' => 'Ukraine Hryvna',
        'GBP' => 'United Kingdom Pound',
        'USD' => 'United States Dollar',
        'UYU' => 'Uruguay Peso',
        'UZS' => 'Uzbekistan Som',
        'VEF' => 'Venezuela Bolivar',
        'VND' => 'Viet Nam Dong',
        'YER' => 'Yemen Rial',
        'ZWD' => 'Zimbabwe Dollar'
    );

    /**
     * Improved Laravel's dd()
     *
     * @param string $var
     * @param bool $exit
     * @param string $title
     */
    public static function dd ($var = '', $exit = true, $title = ''){
        echo '<pre>' . $title . '<br>';
        print_r($var);
        echo '</pre>';
        if ($exit) exit;
    }

    /**
     * Converts string to ASCII
     *
     * @param string $m
     * @return string
     */
    public static function encodeEmails($m){
        $o = '';
        for($i = 0; $i < strlen($m); $i++){
            $o .= '&#'.ord($m[$i]).';';
        }
        return $o;
    }

    /**
     * Sets the <title> tag according to lang/xx/navigation.php
     *
     * @param $r
     * @return mixed
     */
    public static function setHtmlTitles($r) {
        if(!is_object($r)) return Lang::get('errors.title');
        return Lang::get('navigation.' . $r->getPath());
    }

    /**
     * Returns a string with preceding '0' from $i < 10
     *
     * @param $i
     * @return string
     */
    public static function smallerThenTen($i){
        return (intval($i) < 10) ? '0' . $i : $i;
    }

    /**
     * @param $m
     * @param $y
     * @return int
     */
    public static function getLastDayInMonth($m, $y){
        return cal_days_in_month(CAL_GREGORIAN, intval($m), intval($y));
    }

    /**
     * ToDo Unused method
     *
     * @return array|string
     */
    public static function createBreadCrumbs(){
        $langSegments = array();
        $l = '';
        $separator = '>';
        $pipe = '<div class="pipe" style="float:left">' . $separator . '</div>';
        $seg = explode('/', trim(Request::path(), '/'));
        if(empty($seg)) {
            return array();
        }

        $segments = array_filter($seg, function($v) {
            return $v != '';
        });
        foreach($segments as $s){
            $l .= $separator . $s;
            $langSegments[] = '<div class="bread-entry"><a href="' . $l . '">' . trans('navigation.' . $s) . '</a></div>';
        }
        $root = (!empty($langSegments)) ? '<div class="bread-entry"><a href="/">' . ucfirst(Constants::site_name) . '</a></div>' . $pipe : '';

        return $root .  implode($pipe, $langSegments);
    }

    /**
     * Puts the current calendar date in the Session
     *
     * @param null $d UNIX timestamp|null
     * @return mixed
     */
    public static function saveCalendarDate ($d = null) {
        self::dd(Session::get('currentCalendarDate'), false);
        if ($d == null) {
            return Session::put('currentCalendarDate', time());
        }
        self::dd($d, false);
        Session::put('currentCalendarDate', $d);
    }

    /**
     * Gets the current calendar date from the session
     *
     * @return string
     */
    public static function getCalendarDate () {
        $d = new DateTime();
        $s = Session::get('currentCalendarDate');

        if (Session::has('currentCalendarDate')) {
            return $s;
        }
        return $d->format('U');
    }

    /**
     * Checks if a record is editable
     *
     * @param $createdAt date string
     * @return bool
     */
    public static function checkEditableRecord ($createdAt) {
        $set = Setting::getStaticSettings();

        date_default_timezone_set(date_default_timezone_get());
        $editableDate = new DateTime();
        $created_at = new DateTime(str_replace('.', '-', $createdAt));
        $interval = $created_at->diff($editableDate);
        $minutes = $interval->days * 24 * 60;
        $minutes += $interval->h * 60;
        $minutes += $interval->i;
        //Tools::dd($minutes, false);
        //Tools::dd($set->setting_editable_record_time, false);
        if ($minutes < $set->setting_editable_record_time) {
            return 1;
        }
        return 0;

    }

    /**
     *
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}