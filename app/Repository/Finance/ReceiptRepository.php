<?php


namespace App\Repository\Finance;

use App\Models\Group;
use App\Models\Patient;
use App\Models\Service;
use App\Models\FundAccount;
use App\Models\PatientAccount;
use App\Models\ReceiptAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\Finance\ReceiptRepositoryInterface;

class ReceiptRepository implements ReceiptRepositoryInterface
{

    public function index()
    {
        $receipts = ReceiptAccount::with(['patients', 'service', 'group' /* تحميل ترجمات الخدمة/الباقة إذا لزم الأمر */])
            ->orderBy('created_at', 'desc')->get();
        return view('Dashboard.Receipt.index', compact('receipts'));
    }

    public function create()
    {
        $Patients = Patient::get();
        $Services = Service::where('status', 1)->get();
        $GroupedServices = Group::get(); // افترض أن Group لديه name وليس مترجماً حالياً
        return view('Dashboard.Receipt.add', compact('Patients', 'Services', 'GroupedServices')); //  <--- تمرير الباقات
    }


    public function show($id)
    {
        $receipt = ReceiptAccount::with(['patients', 'service', 'group'])->findOrFail($id);
        return view('Dashboard.Receipt.print', compact('receipt'));
    }

    public function store($request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'Debit' => 'required|numeric|min:0',
            'item_type_id' => 'nullable|string', // قيمة مثل "service_X" أو "group_Y"
            'description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $description = $request->description;
            $service_id = null;
            $group_id = null;

            if ($request->filled('item_type_id')) {
                $parts = explode('_', $request->item_type_id);
                $itemType = $parts[0];
                $itemId = $parts[1] ?? null;

                if ($itemType === 'service' && $itemId) {
                    $service = Service::find($itemId);
                    if ($service) {
                        $service_id = $service->id;
                        if (empty($description)) {
                            $description = "رسوم خدمة: " . $service->name;
                        }
                    }
                } elseif ($itemType === 'group' && $itemId) {
                    $group = Group::find($itemId);
                    if ($group) {
                        $group_id = $group->id;
                        if (empty($description)) {
                            $description = "رسوم باقة: " . $group->name; // افترض أن Group لديه name
                        }
                    }
                }
            }

            $receipt_accounts = new ReceiptAccount();
            $receipt_accounts->date = date('Y-m-d');
            $receipt_accounts->patient_id = $request->patient_id;
            $receipt_accounts->service_id = $service_id;
            $receipt_accounts->group_id = $group_id; //  <--- حفظ الباقة
            $receipt_accounts->amount = $request->Debit;
            $receipt_accounts->description = $description;
            $receipt_accounts->save();

            // ... (بقية حفظ fund_accounts و patient_accounts كما هي)
            $fund_accounts = new FundAccount();
            $fund_accounts->date = date('Y-m-d');
            $fund_accounts->receipt_id = $receipt_accounts->id;
            $fund_accounts->Debit = $request->Debit;
            $fund_accounts->credit = 0.00;
            $fund_accounts->save();

            $patient_accounts = new PatientAccount();
            $patient_accounts->date = date('Y-m-d');
            $patient_accounts->patient_id = $request->patient_id;
            $patient_accounts->receipt_id = $receipt_accounts->id;
            $patient_accounts->Debit = 0.00;
            $patient_accounts->credit = $request->Debit;
            $patient_accounts->save();


            DB::commit();
            session()->flash('add', 'تم حفظ سند القبض بنجاح.');
            return redirect()->route('admin.Receipt.index');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $receipt_accounts = ReceiptAccount::findOrFail($id);
        $Patients = Patient::get();
        $Services = Service::where('status', 1)/*->with('translations')*/->get();
        $GroupedServices = Group::/*where('status', 1)->with('translations')->*/get();
        return view('Dashboard.Receipt.edit', compact('receipt_accounts', 'Patients', 'Services', 'GroupedServices')); //  <--- تمرير الباقات
    }

    public function update($request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:receipt_accounts,id',
            'patient_id' => 'required|exists:patients,id',
            'Debit' => 'required|numeric|min:0',
            'item_type_id' => 'nullable|string',
            'description' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $receipt_accounts = ReceiptAccount::findOrFail($validatedData['id']);

            $description = $validatedData['description'];
            $service_id = null;
            $group_id = null;

            if ($request->filled('item_type_id')) {
                $parts = explode('_', $request->item_type_id);
                $itemType = $parts[0];
                $itemId = $parts[1] ?? null;

                if ($itemType === 'service' && $itemId) {
                    $service = Service::find($itemId);
                    if ($service) {
                        $service_id = $service->id;
                        if (empty($description)) {
                            $description = "رسوم خدمة: " . $service->name;
                        }
                    }
                } elseif ($itemType === 'group' && $itemId) {
                    $group = Group::find($itemId);
                    if ($group) {
                        $group_id = $group->id;
                        if (empty($description)) {
                            $description = "رسوم باقة: " . $group->name;
                        }
                    }
                }
            }

            $receipt_accounts->date = date('Y-m-d');
            $receipt_accounts->patient_id = $validatedData['patient_id'];
            $receipt_accounts->service_id = $service_id;
            $receipt_accounts->group_id = $group_id; //  <--- تحديث الباقة
            $receipt_accounts->amount = $validatedData['Debit'];
            $receipt_accounts->description = $description;
            $receipt_accounts->save();

            // ... (بقية تحديث fund_accounts و patient_accounts كما هي) ...
            $fund_accounts = FundAccount::where('receipt_id', $receipt_accounts->id)->first();
            if ($fund_accounts) {
                $fund_accounts->date = date('Y-m-d');
                $fund_accounts->Debit = $validatedData['Debit'];
                $fund_accounts->save();
            }

            $patient_accounts = PatientAccount::where('receipt_id', $receipt_accounts->id)->first();
            if ($patient_accounts) {
                $patient_accounts->date = date('Y-m-d');
                $patient_accounts->patient_id = $validatedData['patient_id'];
                $patient_accounts->credit = $validatedData['Debit'];
                $patient_accounts->save();
            }

            DB::commit();
            session()->flash('edit', 'تم تحديث سند القبض بنجاح.');
            return redirect()->route('admin.Receipt.index');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


    public function destroy($request) // يجب أن يكون $request إما ID أو كائن Request يحتوي على ID
    {
        try {
            $receiptId = $request->id ?? ($request instanceof \Illuminate\Http\Request ? $request->input('id') : null);
            if (!$receiptId) {
                throw new \Exception('Receipt ID not provided for deletion.');
            }
            // لا حاجة لحذف FundAccount و PatientAccount يدوياً إذا كان لديك
            // onDelete('cascade') في المفاتيح الأجنبية لهذه الجداول التي تشير لـ receipt_id
            // إذا لم يكن كذلك، يجب حذفهم يدوياً قبل حذف ReceiptAccount
            // FundAccount::where('receipt_id', $receiptId)->delete();
            // PatientAccount::where('receipt_id', $receiptId)->delete();
            ReceiptAccount::destroy($receiptId);
            session()->flash('delete', 'تم حذف سند القبض بنجاح.');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
