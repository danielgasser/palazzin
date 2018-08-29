<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class BillGeneratorCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'BillGeneratorCommand';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generates all bills.';

    /**
     * Create a new command instance.
     *
     * @param Bill $bill
     * @param Setting set
     */
	public function __construct(Bill $bill, Setting $set)
	{
		$this->bill = $bill;
        $this->set = $set;
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
        $this->info('Bills:');
        $data = $this->bill->generateBills();
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
