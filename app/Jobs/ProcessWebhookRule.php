<?php

namespace App\Jobs;

use App\Helpers\OrderRule;
use App\Helpers\FetchTradeInfo;

class ProcessWebhookRule extends Job
{
    protected $webhookid;
    protected $strategyid;
    protected $ruleId;
    protected $price;
    protected $positionSize;
    protected $action;

    public function __construct($webhookid, $strategyid, $ruleId, $price, $positionSize, $action)
    {
        $this->webhookid = $webhookid;
        $this->strategyid = $strategyid;
        $this->ruleId = $ruleId;
        $this->price = $price;
        $this->positionSize = $positionSize;
        $this->action = $action;
    }

    public function handle()
    {
        $walletId = FetchTradeInfo::fetchWalletId($this->ruleId, $this->webhookid);
        return OrderRule::{"R" . $this->ruleId}(
            $this->webhookid,
            $this->strategyid,
            $this->ruleId,
            $walletId,
            $this->price,
            $this->positionSize,
            $this->action
        );
    }
}
