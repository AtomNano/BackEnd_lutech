<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'search'       => ['nullable', 'string', 'max:255'],
            'status'       => ['nullable', 'in:open,in_progress,resolved,closed'],
            'priority'     => ['nullable', 'in:low,medium,high,urgent'],
            'jenis_device' => ['nullable', 'string', 'max:50'],
            'date_from'    => ['nullable', 'date'],
            'date_to'      => ['nullable', 'date', 'after_or_equal:date_from'],
            'per_page'     => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'         => ['nullable', 'integer', 'min:1'],
        ];
    }
}
