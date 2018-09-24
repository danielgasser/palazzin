<?php

namespace App\Http\Controllers;

use Clan;
use Help;
use Period;
use Setting;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 06.10.14
 * Time: 14:50
 */

class SettingController extends Controller
{

    /**
     * @var array currencies
     */
    public $currencies =  [
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
    ];

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function setSettings()
    {
        $file = null;
        $logo = null;
        $inputs = Input::except('id', '_token', 'setting_login_bg_image_none');
        if (Input::hasFile('setting_login_bg_image')) {
            $file = Input::file('setting_login_bg_image');
        }
        if (Input::hasFile('setting_app_logo')) {
            $logo = Input::file('setting_app_logo');
        }
        $set = Setting::getStaticSettings();

        $t = \Carbon\Carbon::createFromFormat('Y-m-d', $inputs['setting_calendar_start'])->formatLocalized('%Y-%m-%d 00:00:00');
        $p = implode(',', $inputs['setting_payment_methods']);
        $inputs['setting_calendar_start'] = $t;
        $inputs['setting_payment_methods'] = $p;
        if ($file != null) {
            $path = public_path() . '/files/bg_images/login';
            $fileName = self::generateRandomString() . '.' . $file->getClientOriginalExtension();
            $savePath = str_replace(public_path(), '', $path);
            $file->move($path, $fileName);
            $image = Image::make($path . '/' . $fileName);
            $image->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save();
            $inputs['setting_login_bg_image'] = $savePath . '/' . $fileName;
        } else {
            $inputs['setting_login_bg_image'] = Input::get('setting_login_bg_image_none');
        }
        if ($logo != null) {
            $pathLogo = public_path() . '/assets/img';
            $fileNameLogo = self::generateRandomString() . '.' . $logo->getClientOriginalExtension();
            $savePathLogo = str_replace(public_path(), '', $pathLogo);
            $logo->move($pathLogo, $fileNameLogo);
            $imageLogo = Image::make($pathLogo . '/' . $fileNameLogo);
            $imageLogo->resize(182, 55, function ($constraint) {
                $constraint->aspectRatio();
            });
            $imageLogo->save();
            $inputs['setting_app_logo'] = $savePathLogo . '/' . $fileNameLogo;
        } else {
            $inputs['setting_app_logo'] = Input::get('setting_app_logo');
        }
        if (($inputs['setting_calendar_duration'] != $set->setting_calendar_duration) || ($inputs['setting_calendar_start'] != $set->setting_calendar_start)) {
            Period::calculatePeriods();
        }
        $set->update($inputs);
        return redirect()->back()->with('info_message', 'Einstellungen gespeichert')->with('globalSettings', $set->getSettings());
    }

    /**
     * Get all settings
     *
     * @return mixed VIew
     */
    public function showSettings()
    {
        $set = Setting::getStaticSettings();

        return view('logged.admin.settings')
            ->with('globalSettings', $set->getSettings())
            ->with('setting', $set)
            ->with('clans', Clan::pluck('clan_description', 'id'))
            ->with('currencies', $this->currencies);
    }

    /**
     * Get help-texts-setting
     *
     * @return mixed
     */
    public function getHelpSettings()
    {
        $help = new Help();
        return view('logged.admin.settings_help')
            ->with('helpSettings', $help->orderBy('help_topic', 'asc')->get())
            ->with('currencies', $this->currencies);
    }

    /**
     * Saves an exisiting help-text setting
     *
     * @return mixed
     */
    public function setHelpSettings()
    {
        $credentials = [];
        foreach (Input::get('help_text') as $key => $value) {
            $credentials['id'] = Input::get('id')[$key];
            $credentials['help_topic'] = Input::get('help_topic')[$key];
            $help = Help::firstOrCreate($credentials);
            $help->help_text = $value;
            $help->save();
        }
        return Redirect::back();
    }

    public function addHelpTopic()
    {
        $credentials = [
            'help_topic' => htmlspecialchars(strtolower(Input::get('help_topic'))),
            'help_title' => htmlspecialchars(Input::get('help_title')),
            'help_text' => '<p>Keine Hilfe zu diesem Thema gefunden. Bitte melde Dich beim Verwaltungsrat.</p>
<p>Wenn Du Verwaltungsrat bist klicke <a href="https://palazzin.ch/admin/settings/help">hier</a>, um die Hilfetexte zu bearbeiten.</p>',
            'help_lang' => 'de'
        ];
        $topic = Help::firstOrCreate($credentials);
        $topic->save();
        return $this->getHelpSettings();
    }

    /**
     *
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
