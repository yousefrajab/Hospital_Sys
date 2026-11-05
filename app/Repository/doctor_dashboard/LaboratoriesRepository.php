<?php

namespace App\Repository\doctor_dashboard;

use App\Interfaces\doctor_dashboard\LaboratoriesRepositoryInterface;
use App\Models\Invoice;
use App\Models\Laboratorie;

class LaboratoriesRepository implements LaboratoriesRepositoryInterface
{

    public function store($request)
    {
        try {
            // Patient and doctor come from the invoice, which must belong to the logged-in doctor.
            $invoice = Invoice::where('id', $request->invoice_id)
                ->where('doctor_id', auth('doctor')->id())
                ->firstOrFail();
            Laboratorie::create([
                'description'=>$request->description,
                'invoice_id'=>$invoice->id,
                'patient_id'=>$invoice->patient_id,
                'doctor_id'=>$invoice->doctor_id,
            ]);
            session()->flash('add');
            return redirect()->back();
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update($request, $id)
    {
        try {
            $Laboratorie = Laboratorie::where('id', $id)->where('doctor_id', auth('doctor')->id())->firstOrFail();
            $Laboratorie->update([
                'description' => $request->description,
            ]);
            session()->flash('edit');
            return redirect()->back();
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            Laboratorie::where('id', $id)->where('doctor_id', auth('doctor')->id())->firstOrFail()->delete();
            session()->flash('delete');
            return redirect()->back();
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
