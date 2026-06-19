<?php
namespace App\Enums;

enum RolePengguna: string {
    case SPGK = 'spgk';
    case DIETISIEN = 'dietisien';
    case NUTRISIONIS = 'nutrisionis';
    case PERAWAT = 'perawat';
    case ADMIN_TI = 'admin_ti';
}
