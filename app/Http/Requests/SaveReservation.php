<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Reservation;

class SaveReservation extends FormRequest
{

    protected $setting;

    public function __construct()
    {
        $setting = \Illuminate\Support\Facades\App::make(\Setting::class);
        $this->setting = $setting::getStaticSettings();
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'reservation_started_at' => 'bail|required|date|before:reservation_ended_at|after:yesterday',
            'reservation_ended_at' => 'bail|required|date|after:reservation_started_at',
            'reservation_guest_started_at.*' => 'bail|required|date|before:reservation_guest_ended_at.*|after_or_equal:reservation_started_at',
            'reservation_guest_ended_at.*' => 'bail|required|date|after:reservation_guest_started_at.*|before_or_equal:reservation_ended_at',
            'number_nights.*' => 'bail|required|min:1',
            'reservation_guest_guests.*' => 'bail|required|min:1',
            'reservation_guest_price.*' => 'bail|required|min:1',
            'reservation_guest_num.*' => 'bail|required|min:1'
        ];
    }

    public function messages()
    {
        return ['reservation_started_at.after' => ':attribute kann nicht vor heute sein'];
    }

    protected function getValidatorInstance()
    {
        return parent::getValidatorInstance();
    }

    public function withValidator (Validator $validator) {
        $reservation = new Reservation();
        $newBeds = [];
        if (is_null($this->all()['reservation_started_at'])) {
            return back()->withErrors(['empty', trans('reservation.empty_res')]);
        }
        if ($validator->fails()) {
            return back()->withErrors($validator->messages());
        }
        $dates['resStart'] = Reservation::createDbDateFromInput($this->all()['reservation_started_at']);
        $dates['resEnd'] = Reservation::createDbDateFromInput($this->all()['reservation_ended_at']);
        if (array_key_exists('reservation_guest_started_at', $this->all()) && !is_null($this->all()['reservation_guest_started_at'][0])) {
            $dates['guestStart'] = Reservation::createDbDateFromInput($this->all()['reservation_guest_started_at']);
            $dates['guestEnd'] = Reservation::createDbDateFromInput($this->all()['reservation_guest_ended_at']);
            if (!is_null($dates['guestStart']) || !is_null($dates['guestEnd'])) {
                foreach ($this->all()['reservation_guest_started_at'] as $k => $gs) {
                    $key = explode('.', $gs);
                    if (array_key_exists($key[2] . '_' . $key[1] . '_' . $key[0], $newBeds)) {
                        $newBeds[$key[2] . '_' . $key[1] . '_' . $key[0]] += $this->all()['reservation_guest_num'][$k];
                    } else {
                        $newBeds[$key[2] . '_' . $key[1] . '_' . $key[0]] = $this->all()['reservation_guest_num'][$k];
                    }
                }
            } else {
                $validator->errors()->add('guest_date_empty', trans('reservation.guest_empty'));
            }
        }
        $occupiedBeds = $reservation->getReservationsPerPeriodV3($this->all()['periodID'], false);
        $validator->after(function (Validator $validator) use ($occupiedBeds, $newBeds, $dates) {
            if ($this->loopDates($dates['resStart'][0], $dates['resEnd'][0], 'checkOccupiedBeds', [$occupiedBeds, $newBeds])) {
                $validator->errors()->add('w', 'www');
            }
        });
    }

    /**
     * @param string $starStr
     * @param string $endStr
     * @param string $call_func
     * @param        $params
     * @return mixed
     * @throws \Exception
     */
    protected function loopDates (string $starStr, string $endStr, string $call_func, $params)
    {
        $start = new \DateTime($starStr);
        $end = new \DateTime($endStr);
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($start, $interval ,$end);
        foreach ($daterange as $date) {
            $args = [
                'compDate' => $date,
                'occupiedBeds' => $params[0],
                'newBeds' => $params[1]
            ];
            return call_user_func_array([$this, $call_func], [$args]);
        }
    }

    /**
     * @param $args
     * @return bool
     */
    protected function checkOccupiedBeds ($args)
    {
        foreach ($args['occupiedBeds'] as $beds) {
            foreach ($beds as $key => $bed) {
                if (preg_match('/freeBeds/', $key) && array_key_exists($args['compDate']->format('\f\r\e\e\B\e\d\s\_' . 'Y_m_d'), $beds)) {
                    if ($beds[$args['compDate']->format('\f\r\e\e\B\e\d\s\_' . 'Y_m_d')] + $args['newBeds'][$args['compDate']->format('Y_m_d')] > 15) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
