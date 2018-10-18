<?php

namespace App\Controller;

class DbController extends \mysqli
{
    protected $config =array(
        'host'      => '127.0.0.1',
        'db'        => 'test',
        'user'      => 'root',
        'pass'      => '',
        'charset'   => 'utf8'
    );


    /**
     * DbController constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config = array())
    {
        $this->config = array_merge($this->config, $config);

        parent::__construct(
            $this->config['host'],
            $this->config['user'],
            $this->config['pass'],
            $this->config['db']
        );

        if ($this->connect_error) {
            throw new \Exception (
                $this->connect_error,
                $this->connect_errno
            );
        }

        $this->set_charset("utf8");
    }

    /**
     * @param string $tableName
     * @return bool
     */
    public function isTableExists($tableName)
    {
        $query = $this->query("SHOW TABLES LIKE '".$this->escape_string($tableName)."'");
        return !empty($query->num_rows);
    }



}