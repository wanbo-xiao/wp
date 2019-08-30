<?php

class FPI_CLI_COMMAND
{
    protected $service;

    public function __construct()
    {
        $this->service = new Fpi_Service();
    }

    public function insert($args = array(), $assoc_args = array())
    {
        if (count($args) && file_exists($args[0]) ){
            $fileData = file_get_contents($args[0]);
            if ($fileData){
                $result = $this->service->fpi_process_fb_data($fileData);
                foreach ($result as $msg) {
                    if ($msg['status'] == 'success'){
                        WP_CLI::success($msg['msg']);
                    } else {
                        WP_CLI::warning($msg['msg']);
                    }
                }
            } else {
                WP_CLI::error('can not read file');
            }
        } else {
            WP_CLI::error('wrong format');
        }
    }
}
