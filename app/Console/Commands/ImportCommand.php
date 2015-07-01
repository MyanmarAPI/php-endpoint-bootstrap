<?php
/**
 * This file is part of php-endpoint-bootstrap project
 * 
 * @package App\Console\Commands
 * @author Yan Naing <yannaing@hexcores.com>
 * Date: 6/30/15
 * Time: 1:16 PM
 */

namespace App\Console\Commands;

use App\Iora\Reader;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ImportCommand extends Command{

    protected $name = 'iora:import';

    protected $description = 'Import all data from csv files';

    protected $filesystem;

    /**
     * Constructor method for ImportCommand class
     */
    public function __construct()
    {
        parent::__construct();

        $this->filesystem = app('files');
    }

    /**
     * Fire the command
     */
    public function fire()
    {
        $this->iterate();
    }

    protected function iterate()
    {
        $path = $this->input->getOption('path');

        $path = (is_null($path)) ? storage_path('data/' . $this->input->getArgument('model')) : storage_path($path);

        $files = $this->filesystem->files(storage_path('data'));

        if (empty($files))
        {
            return $this->line('There is no file to import in ' . $path);
        }

        $this->info('Importing ...');

        foreach ($files as $file)
        {
            $this->info('Reading - ' . $file);

            $reader = new Reader($file);
            $reader->model($this->input->getArgument('model'));

            $reader->import();

            $this->info($reader->getRows() . ' rows');
        }

        $this->info('Finished importing');
    }

    /**
     * Get command arguments
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'Model name to set data']
        ];
    }

    /**
     * Get command options
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['path', null, InputOption::VALUE_OPTIONAL, '--path="path/to/dir" Directory which contain csv data files']
        ];
    }

}