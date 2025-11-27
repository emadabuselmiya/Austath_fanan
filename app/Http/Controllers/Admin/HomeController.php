<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MainHelper;
use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{

    public function index()
    {
        $admin = auth('admin')->user();

        return view('admin.dashboard');
    }
}
