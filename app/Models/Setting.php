<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 19.09.14
 * Time: 14:53
 */
use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    /**
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     *
     * @var array
     */
    protected $fillable = array(
        'setting_calendar_start',
        'setting_calendar_duration',
        'setting_starting_clan',
        'setting_app_owner',
        'setting_site_name',
        'setting_site_url',
        'setting_payment_methods',
        'setting_num_bed',
        'setting_num_counter_clan_days',
        'setting_global_tax',
        'setting_currency',
        'setting_reminder_days',
        'setting_bill_text',
        'setting_bill_mail_text',
        'setting_bill_deadline',
        'setting_bill_kill',
        'setting_num_counter_days_on_off',
        'setting_counter_clan_on_off',
        'setting_start_reservation_mail_text',
        'setting_login_bg_image',
        'setting_app_logo'
    );

    /**
     *
     * @return mixed
     */
    public function getSettings () {
        return $this->all()->first();
    }

    /**
     *
     * @return mixed
     */
    public static function getStaticSettings () {
        return self::all()->first();
    }

    /**
     *
     * @return mixed
     */
    public function getSettingsArray () {
        return $this->all()->toArray();
    }

    /**
     *
     * @return mixed
     */
    public function getSettingsJson () {
        return $this->all()->first()->toJson();
    }

}