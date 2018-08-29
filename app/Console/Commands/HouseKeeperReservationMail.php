<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class HouseKeeperReservationMail extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'HouseKeeperReservationMail';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
     * @param Reservation $reservation
     * @param Setting $set
	 *
	 */
	public function __construct(Reservation $reservation, Setting $set)
	{
		$this->reservation = $reservation;
		$this->setting = $set->getSettings();
        parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $conn = 		array(
            'mysql' => array(
                'driver'    => 'mysql',
                'host'      => 'gasserd1.mysql.db.hostpoint.ch',
                'database'  => 'gasserd1_palazzin',
                'username'  => 'gasserd1_palaz',
                'password'  => 'xcHwqApY',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ),
        );

        Config::set('database.connections', $conn);
        $this->info(App::environment());
        $this->info('Reservations:');
        $data = $this->reservation->getFutureReservations(true, $this->setting);
        $this->info(Tools::dd($data));

    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
