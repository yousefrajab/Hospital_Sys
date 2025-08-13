<?php

namespace App\Repository\Services;

use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class SingleServiceRepository implements \App\Interfaces\Services\SingleServiceRepositoryInterface
{

    public function index()
    {
        $services = Service::with(['doctor'])->orderBy('created_at', 'desc')->get();
        $doctors = Doctor::where('status', 1)->orderByTranslation('name')->get();
        return view('Dashboard.Services.Single Service.index', compact('services', 'doctors'));
    }

    public function create()
    {
        $doctors = Doctor::where('status', 1)->orderByTranslation('name')->get();
        return view('Dashboard.Services.Single Service.add', compact('doctors'));
    }

    public function store($request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'doctor_id' => 'nullable|exists:doctors,id',
            'status' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $SingleService = new Service();
            $SingleService->price = $request->price;
            // $SingleService->description = $request->description;
            $SingleService->status = $request->status;
            $SingleService->doctor_id = $request->doctor_id;
            $SingleService->save();

            $SingleService->name = $request->name;
            $SingleService->description = $request->description;
            $SingleService->save();

            DB::commit();
            session()->flash('add', trans('messages.success'));
            return redirect()->route('admin.Service.index');

        } catch (\Exception $e) {
            DB::rollback();
            // Log the error: Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => trans('messages.fail') . ': ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id) // هذا الميثود قد لا يُستخدم إذا كانت التعديل يتم عبر مودال من صفحة index
    {
        $service = Service::with(['doctor'])->findOrFail($id);
        $doctors = Doctor::where('status', 1)->orderByTranslation('name')->get();
        return view('Dashboard.Services.Single Service.edit', compact('service', 'doctors')); // افترض أن لديك هذا الـ view
    }

    public function update($request)
    {
        $validatedData = $request->validate([
           'id' => 'required|exists:services,id',
            'name' => [
                'required',
                'string',
                'max:255',

            ],
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'doctor_id' => 'nullable|exists:doctors,id',
            'status' => 'required|boolean',
        ]);


        DB::beginTransaction();
        try {
            $SingleService = Service::findOrFail($validatedData['id']);
            $SingleService->price = $validatedData['price'];
            // $SingleService->description = $validatedData['description'];
            $SingleService->status = $validatedData['status'];
            $SingleService->doctor_id = $validatedData['doctor_id'];
            $SingleService->save();

            // تحديث الترجمات
            $SingleService->name = $validatedData['name'];
            $SingleService->description = $validatedData['description'];
            $SingleService->save();

            DB::commit();
            session()->flash('edit', trans('messages.update'));
            return redirect()->route('admin.Service.index');

        } catch (\Exception $e) {
            DB::rollback();
            // Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => trans('messages.fail') . ': ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($request)
    {
        try {
            $serviceId = $request->id ?? ($request instanceof \Illuminate\Http\Request ? $request->input('id') : null);
            if (!$serviceId) {
                 throw new \Exception('Service ID not provided for deletion.');
            }
            Service::destroy($serviceId);
            session()->flash('delete', trans('messages.delete'));
            return redirect()->route('admin.Service.index');
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => trans('messages.fail') . ': ' . $e->getMessage()]);
        }
    }
}
