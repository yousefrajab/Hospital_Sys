<?php

namespace App\Repository\doctor_dashboard;
use App\Interfaces\doctor_dashboard\RaysRepositoryInterface;
use App\Models\Invoice;
use App\Models\Ray;

class RaysRepository implements RaysRepositoryInterface
{

    public function store($request)
    {
        try {
            // Patient and doctor come from the invoice, which must belong to the logged-in doctor.
            $invoice = Invoice::where('id', $request->invoice_id)
                ->where('doctor_id', auth('doctor')->id())
                ->firstOrFail();
            Ray::create([
                'description'=>$request->description,
                'invoice_id'=>$invoice->id,
                'patient_id'=>$invoice->patient_id,
                'doctor_id'=>$invoice->doctor_id,
            ]);
            session()->flash('add');
            return redirect()->back();
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => error_detail($e)]);
        }
    }

    public function update($request, $id)
    {
        try {
            $Ray = Ray::where('id', $id)->where('doctor_id', auth('doctor')->id())->firstOrFail();
            $Ray->update([
                'description' => $request->description,
            ]);
            session()->flash('edit');
            return redirect()->back();
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => error_detail($e)]);
        }
    }

    public function destroy($id)
    {
        try {
            Ray::where('id', $id)->where('doctor_id', auth('doctor')->id())->firstOrFail()->delete();
            session()->flash('delete');
            return redirect()->back();
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => error_detail($e)]);
        }
    }
}
