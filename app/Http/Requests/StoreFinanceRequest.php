<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFinanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'finance_account_id' => 'nullable|integer|exists:finance_accounts,id',
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'status' => 'nullable|in:pending,approved,rejected',
            'source' => 'nullable|string|max:50',
            'ai_metadata' => 'nullable|array',
            'attachment_path' => 'nullable|string',
            'description' => 'nullable|string|max:500',
            'transaction_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Tipe transaksi (income/expense) wajib diisi.',
            'type.in' => 'Tipe transaksi harus income atau expense.',
            'category.required' => 'Kategori wajib diisi.',
            'amount.required' => 'Nominal wajib diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',
        ];
    }
}
