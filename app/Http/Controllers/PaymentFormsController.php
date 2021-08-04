<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paymentform;
use App\User;
use App\Permission;
use App\Profile;

class PaymentFormsController extends Controller
{
    public function index()
    {
        $paymentform = Paymentform::get();
        $profile = User::findProfile();
        $perm = Permission::permView($profile,9);
        $perm_btn =Permission::permBtns($profile,9);
        if($perm==0)
        {
            return redirect()->route('home');
        }
        else
        {
            return view('admin.payment_forms.payment_form', compact('paymentform','perm_btn'));
        }
    }
    public function GetInfo($id)
    {
        $paymentform = Paymentform::where('id',$id)->first();
        return response()->json(['status'=>true, "data"=>$paymentform]);
    }

    public function store(Request $request)
    {
        $paymentform = new Paymentform;
        $paymentform->name = $request->name;
        $paymentform->save();
        return response()->json(["status"=>true, "message"=>"Forma creada"]);
    }

    public function update(Request $request, $id)
    {
        $paymentform = Paymentform::where('id',$request->id)->update(['name'=>$request->name]);
        return response()->json(['status'=>true, 'message'=>"Forma actualizada"]);
    }

    public function destroy($id)
    {
        $paymentform = Paymentform::find($id);
        $paymentform->delete();
        return response()->json(['status'=>true, "message"=>"Forma eliminada"]);
    }
}
