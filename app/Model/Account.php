<?php

namespace Ebanx\Model;

use Ebanx\Model\Base\ModelFile;
use Ebanx\Model\Base\ModelSession;

class Account extends ModelFile
{
    /**
     * Specify the table name
     *
     * @var string
     */
    protected string $tableName = 'accounts';

    /**
     * Fillable attributes
     *
     * @var array|string[]
     */
    protected array $fillable = [
        'id',
        'balance'
    ];

    protected array $accounts = [
        [
            'id' => '300',
            'balance' => 0
        ],
    ];

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize the db
     */
    public function initDb()
    {
        $this->reset($this->accounts[0]);
    }
}