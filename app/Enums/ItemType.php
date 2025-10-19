<?php

namespace App\Enums;

enum ItemType: string
{
    case STOK = 'stok';
    case PEMINJAMAN = 'peminjaman';

    public function label(): string
    {
        return match ($this) {
            self::STOK => 'Stok',
            self::PEMINJAMAN => 'Peminjaman',
        };
    }
}
