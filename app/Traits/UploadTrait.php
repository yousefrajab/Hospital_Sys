<?php

namespace App\Traits;

use App\Models\Image;
use App\Models\Doctor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{

    public function verifyAndStoreImage(Request $request, $inputname, $foldername, $disk, $imageable_id, $imageable_type)
    {

        if ($request->hasFile($inputname)) {

            // Check img
            if (!$request->file($inputname)->isValid()) {
                flash('Invalid Image!')->error()->important();
                return redirect()->back()->withInput();
            }

            $photo = $request->file($inputname);
            $name = Str::slug($request->input('name'));
            $filename = $name . '.' . $photo->getClientOriginalExtension();

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
    public function verifyAndStoreImageForeach($varforeach, $foldername, $disk, $imageable_id, $imageable_type)
    {
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


    public function Delete_attachment(string $disk, string $path, int $imageable_id, string $imageable_type, ?string $specificFilename = null): void
    {
        Log::info("UploadTrait: Attempting to delete attachment. Disk: [{$disk}], Path: [{$path}], ID: [{$imageable_id}], Type: [{$imageable_type}], Specific Filename: [" . ($specificFilename ?? 'N/A') . "]");

        // 1. حذف الملف الفعلي من نظام التخزين
        if ($path && Storage::disk($disk)->exists($path)) { // تأكد أن المسار ليس فارغًا
            if (Storage::disk($disk)->delete($path)) {
                Log::info("UploadTrait: File '{$path}' deleted successfully from disk '{$disk}'.");
            } else {
                Log::warning("UploadTrait: Failed to delete file '{$path}' from disk '{$disk}'. Check permissions or if it's a directory.");
            }
        } else {
            Log::warning("UploadTrait: File '{$path}' not found on disk '{$disk}' or path is empty, skipping file deletion.");
        }

        // 2. حذف سجل الصورة (أو السجلات) من قاعدة البيانات
        $query = Image::where('imageable_id', $imageable_id)
            ->where('imageable_type', $imageable_type);

        if ($specificFilename) {
            // إذا تم تمرير اسم ملف محدد، استهدفه
            $query->where('filename', $specificFilename);
        } elseif (basename($path) && strpos(basename($path), '.') !== false) {
            // إذا لم يتم تمرير اسم ملف محدد، ولكن المسار يحتوي على اسم ملف (يحتوي على نقطة للامتداد)
            // استخدم اسم الملف من المسار (افترض أن $path هو folder/filename.ext)
            $query->where('filename', basename($path));
        }
        // ملاحظة: إذا كان $specificFilename هو null و $path هو مجرد اسم مجلد (لا يحتوي على اسم ملف بامتداد)،
        // فإن الاستعلام أعلاه سيحذف *جميع* سجلات الصور المرتبطة بـ $imageable_id و $imageable_type.
        // هذا قد يكون السلوك المطلوب عند حذف الكائن الرئيسي (مثل حذف طبيب بكل صوره).
        // إذا كنت تريد تجنب هذا، تأكد دائمًا من تمرير $specificFilename أو مسار كامل للملف.

        $deletedRows = $query->delete();

        if ($deletedRows > 0) {
            Log::info("UploadTrait: {$deletedRows} image record(s) deleted from database for {$imageable_type} ID {$imageable_id}." . ($specificFilename ? " (Filename: {$specificFilename})" : " (Based on path: {$path})"));
        } else {
            // لا تسجل هذا كتحذير إذا كان من الطبيعي ألا يكون هناك سجل (مثلاً عند محاولة حذف صورة لم يتم حفظ سجلها بعد)
            // ولكن سجله إذا كان من المفترض وجود سجل
            Log::info("UploadTrait: No image record found or deleted in database for {$imageable_type} ID {$imageable_id} matching criteria." . ($specificFilename ? " (Filename: {$specificFilename})" : " (Path: {$path})"));
        }
    }
}
