<?php

namespace App\Repository\Sections;

use App\Models\Doctor;
use App\Events\MyEvent;
use App\Models\Section;
use App\Models\SectionTranslation;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Interfaces\Sections\SectionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SectionRepository implements SectionRepositoryInterface
{

    public function index()
    {
        $sections = Section::paginate(10); // هذا يرجع LengthAwarePaginator
        //   event(new MyEvent('welcome',auth()->user()->name));
        return view('Dashboard.Sections.index', compact('sections'));
    }

    public function store($request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:section_translations,name',
            'description' => 'nullable|string'
        ]);

        // لغة الإدخال الحالية (نفترض أنها العربية)
        $inputLang = app()->getLocale(); // لو المستخدم بالعربية، سترجع 'ar'

        // نحدد اللغة الأخرى (التي سنترجم لها)
        $otherLang = $inputLang === 'ar' ? 'en' : 'ar';

        // نستخدم الترجمة من Stichoza
        $tr = new GoogleTranslate($otherLang); // نترجم للغة الأخرى

        // ترجمة الاسم
        $translatedName = $this->translateOrKeep($tr, $request->input('name'));

        // ترجمة الوصف فقط إذا كان موجودًا
        $translatedDesc = $request->input('description') ? $this->translateOrKeep($tr, $request->input('description')) : '';

        // نحفظ البيانات بلغتين
        Section::create([
            $inputLang => [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ],
            $otherLang => [
                'name' => $translatedName,
                'description' => $translatedDesc,
            ]
        ]);

        session()->flash('add');
        return redirect()->route('admin.Sections.index');
    }

    public function update($request)
    {
        $section = Section::findOrFail($request->id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $currentLocale = app()->getLocale(); // اللغة الحالية
        $otherLocale = $currentLocale === 'ar' ? 'en' : 'ar';

        $name = $request->input('name');
        $description = $request->input('description');

        $translator = new GoogleTranslate($otherLocale);

        // ترجمة الاسم
        $translatedName = $this->translateOrKeep($translator, $name);

        // ترجمة الوصف فقط إذا كان موجودًا
        $translatedDesc = $description ? $this->translateOrKeep($translator, $description) : '';

        // تحقق من وجود ترجمة سابقة إذا كانت موجودة، وإذا كانت الترجمة غير دقيقة يمكن للمستخدم تعديلها
        $section->translateOrNew($currentLocale)->name = $name;
        $section->translateOrNew($currentLocale)->description = $description;

        $section->translateOrNew($otherLocale)->name = $translatedName;
        $section->translateOrNew($otherLocale)->description = $translatedDesc;

        $section->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث القسم بنجاح'
            ]);
        }

        session()->flash('edit');
        return redirect()->route('admin.Sections.index');
    }



    public function destroy($request)
    {
        $section = Section::findOrFail($request->id);

        // doctors.section_id and rooms.section_id cascade on delete, so a section in use must not be removed.
        if ($section->doctors()->exists() || $section->rooms()->exists()) {
            $message = 'لا يمكن حذف هذا القسم لأنه مرتبط بأطباء أو غرف. انقلهم إلى قسم آخر أولاً.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->route('admin.Sections.index')->withErrors(['section' => $message]);
        }

        $section->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف القسم بنجاح'
            ]);
        }
        session()->flash('delete');
        return redirect()->route('admin.Sections.index')->with('success', 'تم حذف القسم بنجاح');
    }

    // Google Translate needs internet access; when it fails keep the original text instead of failing the save.
    private function translateOrKeep(GoogleTranslate $translator, string $text): string
    {
        try {
            return $translator->translate($text) ?? $text;
        } catch (\Throwable $e) {
            Log::warning('Section auto-translation failed: ' . $e->getMessage());
            return $text;
        }
    }

    public function show($id)
    {
        $doctors = Section::findOrFail($id)->doctors;
        $section = Section::findOrFail($id);
        return view('Dashboard.Sections.show_doctors', compact('doctors', 'section'));
    }
}
