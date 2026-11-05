<?php

 namespace App\Repository\Dashboard_Ray_Employee;

 use App\Interfaces\Dashboard_Ray_Employee\InvoicesRepositoryInterface;
 use App\Models\Ray;
 use App\Traits\UploadTrait;
 use Illuminate\Support\Facades\DB;

 class InvoicesRepository implements InvoicesRepositoryInterface
 {
     use UploadTrait;

    public function index()
    {

        $invoices = Ray::where('case',0)->get();
         return view('Dashboard.dashboard_RayEmployee.invoices.index',compact('invoices'));
    }

    public function completed_invoices()
    {
        $invoices = Ray::where('case',1)->where('employee_id',auth()->user()->id)->get();
                return view('Dashboard.dashboard_RayEmployee.invoices.completed_invoices',compact('invoices'));
    }

     public function edit($id)
    {
        $invoice = Ray::findorFail($id);
        return view('Dashboard.dashboard_RayEmployee.invoices.add_diagnosis',compact('invoice'));
    }

    public function update($request, $id)
    {
        $request->validate([
            'description_employee' => 'required|string|max:5000',
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        // Only pending requests can be completed, so one employee can't overwrite another's result.
        $invoice = Ray::where('id', $id)->where('case', 0)->firstOrFail();

        $invoice->update([
            'employee_id'=> auth()->user()->id,
            'description_employee'=> $request->description_employee,
            'case'=> 1,
        ]);

        //Upload img
        if( $request->hasFile( 'photos' ) ) {

            foreach ($request->photos as $photo) {
                $this->verifyAndStoreImageForeach($photo,'Rays','upload_image',$invoice->id,'App\Models\Ray');
            }

          }

        session()->flash('edit');
        return redirect()->route('ray_employee.invoices_ray_employee.index');

    }

    public function view_rays($id)
    {
        $rays = Ray::findorFail($id);
        if($rays->employee_id !=auth()->user()->id){
            //abort(404);
            return redirect()->route('404');
        }
        return view('Dashboard.dashboard_RayEmployee.invoices.patient_details', compact('rays'));
    }

 }
