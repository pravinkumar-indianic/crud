<?php

namespace Pravin\Crud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository
                            {name : The name of the view.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    protected $repository = '';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->repository = $this->argument('name');
        $this->repository();
    }
    /**
     * [getTemplate description]
     * @return [type] [description]
     */
    protected function getTemplate()
    {
        return file_get_contents(base_path("vendor/pravin/crud/src/templates/repository.temp"));
    }
    /**
     * [repository description]
     * @return [type] [description]
     */
    protected function repository() {
        $template = str_replace(
            ['{{modelName}}','{{modelNameLowerCase}}'],
            [$this->repository,strtolower($this->repository)],
            $this->getTemplate()
        );
        $path = app_path("/Repositories/{$this->repository}Repository.php");
        if (File::exists($path)) {
            $this->error("Repository already exists.");
        }else{
            file_put_contents(app_path("/Repositories/{$this->repository}Repository.php"), $template);
            $this->info('Repository created successfully.');
        }
    }
}
