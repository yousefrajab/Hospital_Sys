<?php

 namespace App\Repository\doctor_dashboard;

 use App\Interfaces\doctor_dashboard\DiagnosisRepositoryInterface;
 use App\Models\Diagnostic;
 use App\Models\Invoice;
 use Illuminate\Support\Facades\DB;

 class DiagnosisRepository implements DiagnosisRepositoryInterface
 {
     public function store($request)
     {
         DB::beginTransaction();

         try {

             $invoice = $this->doctorInvoice($request->invoice_id);
             $this->invoice_status($invoice->id,3);
             $diagnosis = new Diagnostic();
             $diagnosis->date = date('Y-m-d');
             $diagnosis->diagnosis = $request->diagnosis;
             $diagnosis->medicine = $request->medicine;
             $diagnosis->invoice_id = $invoice->id;
             $diagnosis->patient_id = $invoice->patient_id;
             $diagnosis->doctor_id = $invoice->doctor_id;
             $diagnosis->save();

             DB::commit();
             session()->flash('add');
             return redirect()->back();
         }

         catch (\Exception $e) {
             DB::rollback();
             return redirect()->back()->withErrors(['error' => error_detail($e)]);
         }
     }

     public function show($id)
     {
         $patient_records = Diagnostic::where('patient_id',$id)->get();
         return view('Dashboard.doctor.invoices.patient_record',compact('patient_records'));
     }

     public function addReview($request)
     {
         DB::beginTransaction();
         try {

             $invoice = $this->doctorInvoice($request->invoice_id);
             $this->invoice_status($invoice->id,2);
             $diagnosis = new Diagnostic();
             $diagnosis->date = date('Y-m-d');
             $diagnosis->review_date = date('Y-m-d H:i:s');
             $diagnosis->diagnosis = $request->diagnosis;
             $diagnosis->medicine = $request->medicine;
             $diagnosis->invoice_id = $invoice->id;
             $diagnosis->patient_id = $invoice->patient_id;
             $diagnosis->doctor_id = $invoice->doctor_id;
             $diagnosis->save();

             DB::commit();
             session()->flash('add');
             return redirect()->back();
         }

         catch (\Exception $e) {
             DB::rollback();
             return redirect()->back()->withErrors(['error' => error_detail($e)]);
         }
     }


     // Patient and doctor always come from the invoice, never from the form; the invoice must belong to the logged-in doctor.
     private function doctorInvoice($invoice_id)
     {
         return Invoice::where('id', $invoice_id)
             ->where('doctor_id', auth('doctor')->id())
             ->firstOrFail();
     }

     public function invoice_status($invoice_id,$id_status){
         $invoice_status = Invoice::findorFail($invoice_id);
         $invoice_status->update([
             'invoice_status'=>$id_status
         ]);
     }


 }
