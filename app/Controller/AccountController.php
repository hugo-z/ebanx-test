<?php

namespace Ebanx\Controller;

use Ebanx\Libs\Response;
use Ebanx\Services\Account\Account as AccountService;

class AccountController extends Controller
{
    private AccountService $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new AccountService();
    }

    public function getBalance(string|null $id = null)
    {
        if (is_null($id)) {
            $accountId = $this->request->input('account_id');
            try {
                $account = $this->service->getAccount($accountId);

                Response::json($account, 200);
            } catch (\Exception $e) {
                Response::json(0, $e->getCode());
            }
        }
    }

    public function getAllAccounts()
    {
        Response::json($this->service->getAllAccounts(), 200);
    }

    public function eventAction()
    {
        try {
            $type = $this->request->input('type');

            if (!is_null($type)) {
                $account = $this->service
                    ->{$type}(
                        $this->request->except('type')
                    );

                Response::json($account, 201);
            }

        } catch (\Exception $e) {
            match ($e->getCode()) {
                404 => Response::json(0, $e->getCode()),
                default => Response::json($e->getMessage(), $e->getCode())
            };
        }
    }

    public function reset()
    {
        $this->service->reset();
        Response::json('OK', 200);
    }
}