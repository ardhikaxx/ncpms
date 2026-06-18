<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    public function index() {
        $pasiens = Pasien::paginate(10);
        return view('pasien.index', compact('pasiens'));
    }
}
