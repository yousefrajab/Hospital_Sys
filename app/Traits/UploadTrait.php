<?php

namespace App\Traits;

use App\Models\Image;
use App\Models\Doctor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait UploadTrait{

    public function verifyAndStoreImage(Request $request, $inputname , $foldername , $disk, $imageable_id, $imageable_type) {

        if( $request->hasFile( $inputname ) ) {

            // Check img
            if (!$request->file($inputname)->isValid()) {
                flash('Invalid Image!')->error()->important();
                return redirect()->back()->withInput();
            }

            $photo = $request->file($inputname);
            $name = Str::slug($request->input('name'));
            $filename = $name. '.' . $photo->getClientOriginalExtension();

            // insert Image
            $Image = new Image();
            $Image->filename = $filename;
            $Image->imageable_id = $imageable_id;
            $Image->imageable_type = $imageable_type;
            $Image->save();
            return $request->file($inputname)->storeAs($foldername, $filename, $disk);
        }

        return null;

    }

    // public function verifyAndStoreImageForeach($varforeach , $foldername , $disk, $imageable_id, $imageable_type) {

    //     // insert Image
    //     $Image = new Image();
    //     $Image->filename = $varforeach->getClientOriginalName();
    //     $Image->imageable_id = $imageable_id;
    //     $Image->imageable_type = $imageable_type;
    //     $Image->save();
    //     return $varforeach->storeAs($foldername, $varforeach->getClientOriginalName(), $disk);
    // }


    public function verifyAndStoreImageForeach($varforeach, $foldername, $disk, $imageable_id, $imageable_type) {
        // إنشاء اسم فريد للملف
        $filename = time() . '_' . uniqid() . '.' . $varforeach->getClientOriginalExtension();

        // insert Image
        $Image = new Image();
        $Image->filename = $filename; // استخدم الاسم الفريد بدلاً من الاسم الأصلي
        $Image->imageable_id = $imageable_id;
        $Image->imageable_type = $imageable_type;
        $Image->save();

        // حفظ الملف بالاسم الفريد
        return $varforeach->storeAs($foldername, $filename, $disk);
    }


    public function Delete_attachment($disk,$path,$id){

       // حذف الصورة من المجلد
    if (Storage::disk($disk)->exists($path)) {
        Storage::disk($disk)->delete($path);
    }

    // حذف سجل الصورة من قاعدة البيانات (مع تحديد النوع للتأكد)
    Image::where('imageable_id', $id)
         ->where('imageable_type', Doctor::class)
         ->delete();

    }
}
