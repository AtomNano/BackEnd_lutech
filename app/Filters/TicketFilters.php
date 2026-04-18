<?php

namespace App\Filters;

class TicketFilters extends QueryFilter
{
    public function search($value)
    {
        return $this->builder->where(function ($query) use ($value) {
            $query->whereHas('customer', function ($q) use ($value) {
                $q->where('nama', 'like', "%{$value}%")
                  ->orWhere('whatsapp', 'like', "%{$value}%");
            })->orWhere('subject', 'like', "%{$value}%")
              ->orWhere('merk_device', 'like', "%{$value}%");
        });
    }

    public function status($value)
    {
        return $this->builder->where('status', $value);
    }

    public function priority($value)
    {
        return $this->builder->where('priority', $value);
    }

    public function jenis_device($value)
    {
        return $this->builder->where('jenis_device', $value);
    }

    public function date_from($value)
    {
        return $this->builder->whereDate('created_at', '>=', $value);
    }

    public function date_to($value)
    {
        return $this->builder->whereDate('created_at', '<=', $value);
    }
}
