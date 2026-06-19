<?php
namespace App\Enums;

enum StatusRisikoGizi: string {
    case RISIKO_RENDAH = 'risiko_rendah';
    case RISIKO_SEDANG = 'risiko_sedang';
    case RISIKO_TINGGI = 'risiko_tinggi';
}
