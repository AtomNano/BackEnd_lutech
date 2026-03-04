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
        // Hanya tambahkan saldo jika transaksi langsung dibuat dengan status approved (misal dari panel Web admin)
        if ($finance->status === 'approved') {
            $this->mutateBalance($finance, 'create');
        }
    }

    /**
     * Handle the Finance "updated" event.
     */
    public function updated(Finance $finance): void
    {
        $oldFinance = new Finance($finance->getOriginal());
        $oldFinance->id = $finance->id;

        // Skenario 1: Status berubah dari pending/rejected menjadi approved (Approve Draft)
        if ($oldFinance->status !== 'approved' && $finance->status === 'approved') {
            $this->mutateBalance($finance, 'create');
            return;
        }

        // Skenario 2: Status berubah dari approved menjadi pending/rejected (Batal Approve/Revert)
        if ($oldFinance->status === 'approved' && $finance->status !== 'approved') {
            $this->mutateBalance($oldFinance, 'delete');
            return;
        }

        // Skenario 3: Transaksi tetap approved, namun nominal atau tipe berubah
        if ($oldFinance->status === 'approved' && $finance->status === 'approved') {
            // Cancel old balance mutation
            $this->mutateBalance($oldFinance, 'delete');
            // Apply new balance mutation
            $this->mutateBalance($finance, 'create');
        }
    }

    /**
     * Handle the Finance "deleted" event.
     */
    public function deleted(Finance $finance): void
    {
        // Hanya potong jika yang dihapus itu memang transaksi yang sudah di "approved"
        if ($finance->status === 'approved') {
            $this->mutateBalance($finance, 'delete');
        }
    }

    /**
     * Handle the Finance "restored" event.
     */
    public function restored(Finance $finance): void
    {
        if ($finance->status === 'approved') {
            $this->mutateBalance($finance, 'create');
        }
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
