<?php

namespace App\Http\Livewire;

use App\Models\Doctor;
use App\Models\FundAccount;
use App\Models\Group;
use App\Models\group_invoice;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PatientAccount;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class GroupInvoices extends Component
{
    public $InvoiceSaved = false;
    public $InvoiceUpdated = false;
    public $show_table = true;
    public $updateMode = false;
    public $group_invoice_id;
    public $Group_id;
    public $catchError;
    public $price = 0;
    public $patient_id, $doctor_id, $section_id, $type;
    public $discount_value = 0;
    public $tax_rate = 0;



    public function render()
    {
        return view('livewire.group_invoices.group-invoices', [
            'group_invoices' => Invoice::where('invoice_type', 2)->get(),
            'Patients' => Patient::all(),
            'Doctors' => Doctor::all(),
            'Groups' => Group::all(),
            'subtotal' => $Total_after_discount = ((is_numeric($this->price) ? $this->price : 0)) - ((is_numeric($this->discount_value) ? $this->discount_value : 0)),
            'tax_value' => $Total_after_discount * ((is_numeric($this->tax_rate) ? $this->tax_rate : 0) / 100)
        ]);
    }


    public function show_form_add()
    {
        $this->show_table = false;
    }


    public function get_section()
    {
        $doctor_id = Doctor::with('section')->where('id', $this->doctor_id)->first();
        $this->section_id = $doctor_id->section->name;
    }

    public function get_price()
    {
        $this->price = Group::where('id', $this->Group_id)->first()->Total_before_discount;
        $this->discount_value = Group::where('id', $this->Group_id)->first()->discount_value;
        $this->tax_rate = Group::where('id', $this->Group_id)->first()->tax_rate;
    }


    public function store()
    {
        // The invoice and its ledger row must be saved together.
        $this->catchError = null;
        DB::beginTransaction();
        $this->saveInvoice();
        if ($this->catchError) {
            DB::rollBack();
            // Nothing was saved: drop the success flags and keep the form open with its input.
            $this->InvoiceSaved = false;
            $this->InvoiceUpdated = false;
            $this->show_table = false;
        } else {
            DB::commit();
        }
    }

    /** Clear the form fields after a successful save. */
    private function resetForm()
    {
        $this->reset(['patient_id', 'doctor_id', 'section_id', 'type', 'Group_id', 'group_invoice_id', 'updateMode']);
        $this->price = 0;
        $this->discount_value = 0;
        $this->tax_rate = 0;
    }

    private function saveInvoice()
    {

        if ($this->type == 1) {

            try {
                if ($this->updateMode) {

                    $group_invoices = Invoice::findorfail($this->group_invoice_id);
                    $group_invoices->invoice_type = 2;
                    $group_invoices->invoice_date = date('Y-m-d');
                    $group_invoices->patient_id = $this->patient_id;
                    $group_invoices->doctor_id = $this->doctor_id;
                    $group_invoices->section_id = Doctor::findOrFail($this->doctor_id)->section_id;
                    $group_invoices->Group_id = $this->Group_id;
                    $group_invoices->price = $this->price;
                    $group_invoices->discount_value = $this->discount_value;
                    $group_invoices->tax_rate = $this->tax_rate;
                    $group_invoices->tax_value = ($this->price - $this->discount_value) * ((is_numeric($this->tax_rate) ? $this->tax_rate : 0) / 100);
                    $group_invoices->total_with_tax = $group_invoices->price -  $group_invoices->discount_value + $group_invoices->tax_value;
                    $group_invoices->type = $this->type;
                    $group_invoices->save();

                    // The payment type may have changed (cash <-> credit): keep only the matching ledger row.
                    PatientAccount::where('invoice_id', $this->group_invoice_id)->delete();
                    $fund_accounts = FundAccount::where('invoice_id', $this->group_invoice_id)->first() ?? new FundAccount();
                    $fund_accounts->date = date('Y-m-d');
                    $fund_accounts->invoice_id = $group_invoices->id;
                    $fund_accounts->Debit = $group_invoices->total_with_tax;
                    $fund_accounts->credit = 0.00;
                    $fund_accounts->save();
                    $this->InvoiceUpdated = true;
                    $this->show_table = true;
                }

                else {

                    $group_invoices = new Invoice();
                    $group_invoices->invoice_type = 2;
                    $group_invoices->invoice_date = date('Y-m-d');
                    $group_invoices->patient_id = $this->patient_id;
                    $group_invoices->doctor_id = $this->doctor_id;
                    $group_invoices->section_id = Doctor::findOrFail($this->doctor_id)->section_id;
                    $group_invoices->Group_id = $this->Group_id;
                    $group_invoices->price = $this->price;
                    $group_invoices->discount_value = $this->discount_value;
                    $group_invoices->tax_rate = $this->tax_rate;
                    $group_invoices->tax_value = ($this->price - $this->discount_value) * ((is_numeric($this->tax_rate) ? $this->tax_rate : 0) / 100);
                    $group_invoices->total_with_tax = $group_invoices->price -  $group_invoices->discount_value + $group_invoices->tax_value;
                    $group_invoices->type = $this->type;
                    $group_invoices->save();

                    $fund_accounts = new FundAccount();
                    $fund_accounts->date = date('Y-m-d');
                    $fund_accounts->invoice_id = $group_invoices->id;
                    $fund_accounts->Debit = $group_invoices->total_with_tax;
                    $fund_accounts->credit = 0.00;
                    $fund_accounts->save();
                    $this->InvoiceSaved = true;
                    $this->show_table = true;
                    $this->resetForm();
                }
            } catch (\Exception $e) {
                $this->catchError = error_detail($e);
            }
        }

        else {

            try {
                if ($this->updateMode) {

                    $group_invoices = Invoice::findorfail($this->group_invoice_id);
                    $group_invoices->invoice_type = 2;
                    $group_invoices->invoice_date = date('Y-m-d');
                    $group_invoices->patient_id = $this->patient_id;
                    $group_invoices->doctor_id = $this->doctor_id;
                    $group_invoices->section_id = Doctor::findOrFail($this->doctor_id)->section_id;
                    $group_invoices->Group_id = $this->Group_id;
                    $group_invoices->price = $this->price;
                    $group_invoices->discount_value = $this->discount_value;
                    $group_invoices->tax_rate = $this->tax_rate;
                    $group_invoices->tax_value = ($this->price - $this->discount_value) * ((is_numeric($this->tax_rate) ? $this->tax_rate : 0) / 100);
                    $group_invoices->total_with_tax = $group_invoices->price -  $group_invoices->discount_value + $group_invoices->tax_value;
                    $group_invoices->type = $this->type;
                    $group_invoices->save();

                    // The payment type may have changed (cash <-> credit): keep only the matching ledger row.
                    FundAccount::where('invoice_id', $this->group_invoice_id)->delete();
                    $patient_accounts = PatientAccount::where('invoice_id', $this->group_invoice_id)->first() ?? new PatientAccount();
                    $patient_accounts->date = date('Y-m-d');
                    $patient_accounts->invoice_id = $group_invoices->id;
                    $patient_accounts->patient_id = $group_invoices->patient_id;
                    $patient_accounts->Debit = $group_invoices->total_with_tax;
                    $patient_accounts->credit = 0.00;
                    $patient_accounts->save();
                    $this->InvoiceUpdated = true;
                    $this->show_table = true;
                    $this->resetForm();
                }

                else {


                    $group_invoices = new Invoice();
                    $group_invoices->invoice_type = 2;
                    $group_invoices->invoice_date = date('Y-m-d');
                    $group_invoices->patient_id = $this->patient_id;
                    $group_invoices->doctor_id = $this->doctor_id;
                    $group_invoices->section_id = Doctor::findOrFail($this->doctor_id)->section_id;
                    $group_invoices->Group_id = $this->Group_id;
                    $group_invoices->price = $this->price;
                    $group_invoices->discount_value = $this->discount_value;
                    $group_invoices->tax_rate = $this->tax_rate;
                    $group_invoices->tax_value = ($this->price - $this->discount_value) * ((is_numeric($this->tax_rate) ? $this->tax_rate : 0) / 100);
                    $group_invoices->total_with_tax = $group_invoices->price -  $group_invoices->discount_value + $group_invoices->tax_value;
                    $group_invoices->type = $this->type;
                    $group_invoices->save();

                    $patient_accounts = new PatientAccount();
                    $patient_accounts->date = date('Y-m-d');
                    $patient_accounts->invoice_id = $group_invoices->id;
                    $patient_accounts->patient_id = $group_invoices->patient_id;
                    $patient_accounts->Debit = $group_invoices->total_with_tax;
                    $patient_accounts->credit = 0.00;
                    $patient_accounts->save();
                    $this->InvoiceSaved = true;
                    $this->show_table = true;
                    $this->resetForm();
                }
            } catch (\Exception $e) {
                $this->catchError = error_detail($e);
            }
        }
    }


    public function edit($id)
    {

        $this->show_table = false;
        $this->updateMode = true;
        $group_invoices = Invoice::findorfail($id);
        $this->group_invoice_id = $group_invoices->id;
        $this->patient_id = $group_invoices->patient_id;
        $this->doctor_id = $group_invoices->doctor_id;
        $this->section_id = optional(Section::find($group_invoices->section_id))->name;
        $this->Group_id = $group_invoices->Group_id;
        $this->price = $group_invoices->price;
        $this->discount_value = $group_invoices->discount_value;
        $this->tax_rate = $group_invoices->tax_rate;
        $this->tax_value = $group_invoices->tax_value;
        $this->type = $group_invoices->type;
    }

    public function delete($id)
    {
        $this->group_invoice_id = $id;
        session()->flash('delete');
        return redirect()->to('admin/group_invoices')->with('success', trans('AdminSb.invoice_deleted_successfully'));
    }

    public function prepareForDelete($id)
    {
        $this->group_invoice_id = $id;
        Log::info('Preparing to delete invoice ID: ' . $id);

    }

    public function destroy()
    {
        if ($this->group_invoice_id) {
            Log::info('Attempting to destroy invoice ID: ' . $this->group_invoice_id);
            try {
                Invoice::destroy($this->group_invoice_id);
                session()->flash('message', trans('AdminSb.invoice_deleted_successfully'));
                Log::info('Invoice ID: ' . $this->group_invoice_id . ' destroyed successfully.');
                $this->dispatch('close-delete-modal');
                $this->group_invoice_id = null;

                return redirect()->to('admin/group_invoices');

            } catch (\Exception $e) {
                Log::error('Error destroying invoice ID: ' . $this->group_invoice_id . ' - ' . $e->getMessage());
                session()->flash('error', trans('AdminSb.error_deleting_invoice'));
                $this->dispatch('close-delete-modal');
                $this->group_invoice_id = null;
            }
        } else {
            Log::warning('Destroy called without a group_invoice_id set.');
            session()->flash('error', trans('AdminSb.invoice_not_selected_for_deletion'));
        }
    }

    public function print($id)
    {
        $single_invoice = Invoice::findorfail($id);
        return Redirect::route('admin.group_Print_single_invoices', [
            'invoice_date' => $single_invoice->invoice_date,
            'doctor_id' => $single_invoice->Doctor->name,
            'section_id' => $single_invoice->Section->name,
            'Group_id' => $single_invoice->Group->name,
            'type' => $single_invoice->type,
            'price' => $single_invoice->price,
            'discount_value' => $single_invoice->discount_value,
            'tax_rate' => $single_invoice->tax_rate,
            'total_with_tax' => $single_invoice->total_with_tax,
        ]);
    }
}
