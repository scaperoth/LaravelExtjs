<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Config;

class CreateDBCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the db from the information given in .env file';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        Config::set('database.default', 'mysql_admin');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
			$schema = env('DB_DATABASE');
			$host = env('DB_HOST');
			$username = env('DB_USERNAME');
			$pass = env('DB_PASSWORD');
			
      $currentStep = "Creating DB $schema";
      $this->info("$currentStep...");
      $result = DB::statement("CREATE DATABASE IF NOT EXISTS $schema");
			
			if($result){
      	$this->info("...Complete.");
	      $this->info("");
			}else{
				$this->error("Error during $currentStep");
				die();
			}

      $currentStep = "Creating User '$username'@'$host'";
      $this->info("$currentStep...");
      $result = DB::statement("CREATE USER '$username'@'$host' IDENTIFIED BY '$pass'");
			
			if($result){
				$this->info("...Complete.");
				$this->info("");
			}else{
				$this->error("Error during $currentStep");
				die();
			}

      $currentStep = "Creating Privileges for '$username'@'$host'";
      $this->info('Creating Privileges...');
      $result = DB::statement("GRANT ALL ON $schema.* TO '$username'@'$host'");
			
			if($result){
      	$this->info("...Complete.");
	      $this->info("");
			}else{
				$this->error("Error during $currentStep");
				die();
			}
			
      $this->info("All done! Goodbye.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }
}
