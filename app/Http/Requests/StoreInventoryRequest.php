<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    public function getInventoryData($oldJumlah, $itemId)
    {
        $newJumlah = $this->input('jumlah');
        $selisih = $newJumlah - $oldJumlah;

        if ($selisih === 0) {
            return null;
        }

        return [
            'item_id' => $itemId,
            'tipe' => $selisih > 0 ? 'masuk' : 'keluar',
            'jumlah' => abs($selisih),
            'status' => $selisih < 0 ? ($this->input('status') ?? null) : null,
        ];
    }
}
