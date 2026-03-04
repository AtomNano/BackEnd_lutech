<?php

namespace App\Observers;

use App\Models\Finance;

class FinanceObserver
{
    /**
     * Handle the Finance "created" event.
     */
    public function created(Finance $finance): void
    {
        $this->mutateBalance($finance, 'create');
    }

    /**
     * Handle the Finance "updated" event.
     */
    public function updated(Finance $finance): void
    {
        // Cancel old balance mutation
        $oldFinance = new Finance($finance->getOriginal());
        $oldFinance->id = $finance->id;
        $this->mutateBalance($oldFinance, 'delete');

        // Apply new balance mutation
        $this->mutateBalance($finance, 'create');
    }

    /**
     * Handle the Finance "deleted" event.
     */
    public function deleted(Finance $finance): void
    {
        $this->mutateBalance($finance, 'delete');
    }

    /**
     * Handle the Finance "restored" event.
     */
    public function restored(Finance $finance): void
    {
        //
    }

    /**
     * Handle the Finance "force deleted" event.
     */
    public function forceDeleted(Finance $finance): void
    {
        //
    }

    private function mutateBalance(Finance $finance, string $action): void
    {
        // Only proceed if there is a linked FinanceAccount
        if (!$finance->finance_account_id) {
            return;
        }

        $account = $finance->financeAccount;
        if (!$account) {
            return;
        }

        $amount = (float) $finance->amount;

        if ($action === 'create') {
            if ($finance->type === 'income') {
                $account->balance += $amount;
            } else {
                $account->balance -= $amount;
            }
        } elseif ($action === 'delete') {
            if ($finance->type === 'income') {
                $account->balance -= $amount;
            } else {
                $account->balance += $amount;
            }
        }

        $account->save();
    }
}
