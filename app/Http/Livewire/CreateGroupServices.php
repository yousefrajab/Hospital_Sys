<?php

namespace App\Http\Livewire;

use App\Models\Group;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateGroupServices extends Component
{
    public $GroupsItems = [];
    public $allServices = [];
    // public $availableDoctors = []; // لم تعد مستخدمة بهذا الشكل
    public $discount_value = 0;
    public $taxes = 17;
    public $name_group;
    public $notes;
    public $ServiceSaved = false;    // Flag for individual service item saved state in UI
    public $ServiceUpdated = false;  // Flag for overall group update state in UI
    public $show_table = true;
    public $updateMode = false;
    public $group_id;

    public function mount()
    {
        // جلب جميع الخدمات المفعلة مع تحميل علاقات الطبيب (وقسمه) وترجماتهم
        $this->allServices = Service::where('status', 1) // فقط الخدمات المفعلة

            ->get();
    }

    public function render()
    {
        $total = 0;
        foreach ($this->GroupsItems as $groupItem) {
            if ($groupItem['is_saved'] && isset($groupItem['service_price']) && isset($groupItem['quantity'])) {
                $total += $groupItem['service_price'] * $groupItem['quantity'];
            }
        }

        // تحميل ترجمات المجموعات للعرض في الجدول
        $groups = Group::orderBy('created_at', 'desc')->get();

        return view('livewire.GroupServices.create-group-services', [
            'groups' => $groups,
            'subtotal' => $Total_after_discount = $total - ((is_numeric($this->discount_value) ? $this->discount_value : 0)),
            'total' => $Total_after_discount * (1 + (is_numeric($this->taxes) ? $this->taxes : 0) / 100)
        ]);
    }


    public function addService()
    {
        foreach ($this->GroupsItems as $key => $groupItem) {
            if (!$groupItem['is_saved']) {
                $this->addError('GroupsItems.' . $key, 'يجب حفظ هذه الخدمة قبل إنشاء خدمة جديدة.');
                return;
            }
        }

        $this->GroupsItems[] = [
            'service_id' => '',
            'quantity' => 1,
            'is_saved' => false,
            'service_name' => '',
            'service_price' => 0,
            'service_doctor_name' => '', //  <--- لحفظ اسم طبيب الخدمة المفردة (اختياري للعرض)
            'service_section_name' => '', // <--- لحفظ اسم قسم الخدمة المفردة (اختياري للعرض)
        ];
        $this->ServiceSaved = false; // هذا السطر لا يبدو أن له تأثير كبير هنا
    }


    public function editService($index)
    {
        foreach ($this->GroupsItems as $key => $groupItem) {
            if (!$groupItem['is_saved']) {
                $this->addError('GroupsItems.' . $key, 'This line must be saved before editing another.');
                return;
            }
        }

        $this->GroupsItems[$index]['is_saved'] = false;
    }


    public function saveService($index)
    {
        $this->resetErrorBag();
        $rules = [
            'GroupsItems.' . $index . '.service_id' => 'required|exists:services,id', // التأكد أن الخدمة موجودة
            'GroupsItems.' . $index . '.quantity' => 'required|numeric|min:1',
        ];
        $this->validate($rules, [
            'GroupsItems.' . $index . '.service_id.required' => 'حقل الخدمة مطلوب.',
            'GroupsItems.' . $index . '.service_id.exists' => 'الخدمة المختارة غير صالحة.',
            'GroupsItems.' . $index . '.quantity.required' => 'حقل الكمية مطلوب.',
            'GroupsItems.' . $index . '.quantity.numeric' => 'الكمية يجب أن تكون رقماً.',
            'GroupsItems.' . $index . '.quantity.min' => 'الكمية يجب أن تكون 1 على الأقل.',
        ]);

        // جلب الخدمة مع علاقاتها من مجموعة allServices التي تم تحميلها في mount
        $selectedService = $this->allServices->find($this->GroupsItems[$index]['service_id']);

        if (!$selectedService) { // احتياطي إضافي
            $this->addError('GroupsItems.' . $index . '.service_id', 'الخدمة المختارة غير موجودة.');
            return;
        }

        // استخدام الاسم المترجم للخدمة (يفترض أن locale الحالي صحيح)
        $this->GroupsItems[$index]['service_name'] = $selectedService->name; // يعتمد على إعدادات astrotomic
        $this->GroupsItems[$index]['service_price'] = $selectedService->price;
        $this->GroupsItems[$index]['is_saved'] = true;

        // حفظ اسم الطبيب والقسم (اختياري، للعرض في الـ Blade إذا أردت)
        if ($selectedService->doctor) {
            $this->GroupsItems[$index]['service_doctor_name'] = $selectedService->doctor->name;
            if ($selectedService->doctor->section) {
                $this->GroupsItems[$index]['service_section_name'] = $selectedService->doctor->section->name;
            } else {
                $this->GroupsItems[$index]['service_section_name'] = 'غير محدد';
            }
        } else {
            $this->GroupsItems[$index]['service_doctor_name'] = 'غير محدد';
            $this->GroupsItems[$index]['service_section_name'] = 'N/A';
        }
    }
    public function removeService($index)
    {
        unset($this->GroupsItems[$index]);
        $this->GroupsItems = array_values($this->GroupsItems);
    }

    public function saveGroup()
    {

        // update
        if ($this->updateMode) {
            $Groups = Group::find($this->group_id);
            $total = 0;
            foreach ($this->GroupsItems as $groupItem) {
                if ($groupItem['is_saved'] && $groupItem['service_price'] && $groupItem['quantity']) {
                    // الاجمالي قبل الخصم
                    $total += $groupItem['service_price'] * $groupItem['quantity'];
                }
            }
            //الاجمالي قبل الخصم
            $Groups->total_before_discount = $total;
            // قيمة الخصم
            $Groups->discount_value = $this->discount_value;
            // الاجمالي بعد الخصم
            $Groups->total_after_discount = $total - ((is_numeric($this->discount_value) ? $this->discount_value : 0));
            //  نسبة الضريبة
            $Groups->tax_rate = $this->taxes;
            // الاجمالي + الضريبة
            $Groups->total_with_tax = $Groups->total_after_discount * (1 + (is_numeric($this->taxes) ? $this->taxes : 0) / 100);
            $Groups->save();
            // حفظ الترجمة
            $Groups->name = $this->name_group;
            $Groups->notes = $this->notes;
            $Groups->save();
            // حفظ العلاقة
            $Groups->service_group()->detach();
            foreach ($this->GroupsItems as $GroupsItem) {
                $Groups->service_group()->attach($GroupsItem['service_id'], ['quantity' => $GroupsItem['quantity']]);
            }

            $this->ServiceSaved = false;
            $this->ServiceUpdated = true;
        } else {

            // insert
            $Groups = new Group();
            $total = 0;

            foreach ($this->GroupsItems as $groupItem) {
                if ($groupItem['is_saved'] && $groupItem['service_price'] && $groupItem['quantity']) {
                    // الاجمالي قبل الخصم
                    $total += $groupItem['service_price'] * $groupItem['quantity'];
                }
            }

            //الاجمالي قبل الخصم
            $Groups->Total_before_discount = $total;
            // قيمة الخصم
            $Groups->discount_value = $this->discount_value;
            // الاجمالي بعد الخصم
            $Groups->Total_after_discount = $total - ((is_numeric($this->discount_value) ? $this->discount_value : 0));
            //  نسبة الضريبة
            $Groups->tax_rate = $this->taxes;
            // الاجمالي + الضريبة
            $Groups->Total_with_tax = $Groups->Total_after_discount * (1 + (is_numeric($this->taxes) ? $this->taxes : 0) / 100);
            $Groups->save();

            // حفظ الترجمة
            $Groups->name =  $this->name_group;
            $Groups->notes = $this->notes;
            $Groups->save();

            // حفظ العلاقة
            foreach ($this->GroupsItems as $GroupsItem) {
                $Groups->service_group()->attach($GroupsItem['service_id'], ['quantity' => $GroupsItem['quantity']]);
            }

            $this->reset('GroupsItems', 'name_group', 'notes');
            $this->discount_value = 0;
            $this->ServiceSaved = true;
        }
        return redirect()->to('admin/Add_GroupServices')->with('تم التحديث ');
    }

    public function show_form_add()
    {
        $this->show_table = false;
    }

    public function edit($id)
    {
        $this->show_table = false;
        $this->updateMode = true;
        $group = Group::where('id', $id)->first();
        $this->group_id = $id;

        $this->reset('GroupsItems', 'name_group', 'notes');
        $this->name_group = $group->name;
        $this->notes = $group->notes;

        $this->discount_value = intval($group->discount_value);
        $this->ServiceSaved = false;

        foreach ($group->service_group as $serviceGroup) {
            $this->GroupsItems[] = [
                'service_id' => $serviceGroup->id,
                'quantity' => $serviceGroup->pivot->quantity,
                'is_saved' => true,
                'service_name' => $serviceGroup->name,
                'service_price' => $serviceGroup->price
            ];
        }
    }

    public function delete($id)
    {
        Group::destroy($id);
        return redirect()->to('admin/Add_GroupServices');
    }
}
