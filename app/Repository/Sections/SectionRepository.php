<?php

namespace App\Repository\Sections;

use App\Models\Doctor;
use App\Events\MyEvent;
use App\Models\Section;
use App\Models\SectionTranslation;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Interfaces\Sections\SectionRepositoryInterface;

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
        $translatedName = $tr->translate($request->input('name'));

        // ترجمة الوصف فقط إذا كان موجودًا
        $translatedDesc = $request->input('description') ? $tr->translate($request->input('description')) : '';

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
        $translatedName = $translator->translate($name);

        // ترجمة الوصف فقط إذا كان موجودًا
        $translatedDesc = $description ? $translator->translate($description) : '';

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

    public function show($id)
    {
        $doctors = Section::findOrFail($id)->doctors;
        $section = Section::findOrFail($id);
        return view('Dashboard.Sections.show_doctors', compact('doctors', 'section'));
    }
}
