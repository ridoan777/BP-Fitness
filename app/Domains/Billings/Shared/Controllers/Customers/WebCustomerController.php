<?php

namespace App\Domains\Billings\Shared\Controllers\Customers;

use App\Http\Controllers\Controller;
use Throwable;

class WebCustomerController extends Controller
{
   public function index()
   {
      try {
         return view('Admin.sidebar.Billings.Customers.index');
      } catch (Throwable $e) {
         return redirect()->back()->with('error', 'Action Error: ' . $e->getMessage());
      }
   }
}
