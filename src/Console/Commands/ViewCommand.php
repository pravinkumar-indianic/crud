<?php

namespace Pravin\Crud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view
                            {name : The name of the view.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view.';

    protected $modelName = '';

    protected $modelNameLowerCase = '';


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
        $this->modelName = $this->argument('name');
        $this->modelNameLowerCase = strtolower($this->modelName);
        $this->views();
    }
    /**
     * [getTemplate description]
     * @return [type] [description]
     */
    protected function getTemplate()
    {
        return file_get_contents(base_path("vendor/pravin/crud/src/templates/view.temp"));
    }
    /**
     * [views description]
     * @return [type] [description]
     */
    protected function views() {
        if (strpos($this->modelName, '/')) {
            list($folder,$file) = explode('/', $this->modelName);
            $folder = strtolower($folder);
            if(!is_dir($directory = resource_path('/views/'.$folder))) {
                File::makeDirectory($directory, 0777, true);
            }
            $template = str_replace(
                ['{{modelNameLowerCase}}','{{userType}}','{{modelName}}','{{Prefix}}'],
                [strtolower($file),'layout.'.strtolower($folder),$file,strtolower($folder).'/'],
                $this->getTemplate()
            );
            $file = strtolower($file);
            $path = resource_path("/views/{$folder}/{$file}.blade.php");
            if (File::exists($path)) {
                $this->error("View already exists.");
            }else{
                file_put_contents(resource_path("/views/{$folder}/{$file}.blade.php"), $template);
                $this->info('View created successfully.');
            }
        }else{
            $template = str_replace(
                ['{{modelNameLowerCase}}','{{userType}}','{{modelName}}','{{Prefix}}'],
                [$this->modelNameLowerCase, 'layout.master',$this->modelName,''],
                $this->getTemplate()
            );
            $path = resource_path("/views/{$this->modelNameLowerCase}.blade.php");
            if (File::exists($path)) {
                $this->error("View already exists.");
            }else{
                file_put_contents(resource_path("/views/{$this->modelNameLowerCase}.blade.php"), $template);
                $this->info('View created successfully.');
            }
        }
    }
}