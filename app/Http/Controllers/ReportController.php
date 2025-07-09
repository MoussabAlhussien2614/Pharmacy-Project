<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesReportRequest;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\OrderResource;
use App\Models\Medicine;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function lowStock() {
        $meds = Medicine::where('quantity','<',10)->with('category','creator')->get();
        return MedicineResource::collection($meds);

    }

    public function expiring() {
        $date = Carbon::now()->addMonth();
        $meds = Medicine::whereBetween('expiry_date',[now(),$date])->with('category','creator')->get();
        return MedicineResource::collection($meds);
    }

   public function sales(SalesReportRequest $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $orders = Order::whereBetween('created_at', [$from, $to])->get();

        return OrderResource::collection($orders);
    }




}
