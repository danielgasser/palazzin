<?php

namespace App\Console\Commands;

use App\Notifications\BirthdayMessage;
use Illuminate\Console\Command;

class BirthDayReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BirthDayReminder {uID*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets\'s all users birthdays';

    protected $user;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($uID = '')
    {
        parent::__construct();
        $this->user = \User::find($uID);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }

    public function fire()
    {
        echo 'kfdgdgs';
        $this->user->sendBirthdayMail();
        $this->user->notify(new BirthdayMessage($this->user->user_first_name));
    }
}
