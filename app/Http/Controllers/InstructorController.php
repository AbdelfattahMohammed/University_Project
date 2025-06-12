<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Instructor;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        // هذه الدالة قد تعرض قائمة بجميع الأقسام أو توجيه المستخدم لصفحات الأقسام
        return view('instructor'); // افترض أن هذا هو View الرئيسي للمدرسين
    }

    /**
     * دالة مساعدة لجلب بيانات المدرسين لقسم معين.
     *
     * @param string $departmentName اسم القسم (مثال: 'IS', 'CS', 'AI', 'BIO')
     * @param string $viewName اسم ملف الـ Blade المراد عرضه (مثال: 'is_instructor')
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    private function getDepartmentInstructors(string $departmentName, string $viewName)
    {
        $department = Department::where('department_name', $departmentName)->first();

        // تحقق مما إذا كان القسم موجودًا
        if (!$department) {
            // سجل الخطأ للمراجعة في سجلات Laravel
            Log::error("Department not found for name: {$departmentName}");
            return redirect()->back()->with('error', 'القسم المحدد غير موجود.');
        }

        // جلب رئيس القسم، إذا كان عمود 'head_of_department' يخزن الاسم مباشرة.
        // إذا كان يخزن ID لأستاذ، يجب تعديل هذا لجلب كائن الأستاذ.
        $headOfDepartment = $department->head_of_department ?? 'لا يوجد رئيس قسم محدد';

        // جلب جميع المدرسين التابعين لهذا القسم
        // وتحميل المقررات التي يدرسونها والتي تتبع هذا القسم تحديدًا
        $instructors = Instructor::where('department_id', $department->id)
            ->with(['courses' => function ($query) use ($department) {
                // فلترة المقررات بحيث تكون فقط تلك المرتبطة بالقسم الحالي
                $query->whereHas('departments', function ($q) use ($department) {
                    $q->where('departments.id', $department->id);
                });
            }])
            ->get();

        // تمرير رئيس القسم والمدرسين وكائن القسم نفسه إلى الـ View
        return view($viewName, compact('headOfDepartment', 'instructors', 'department'));
    }

    public function isIndex()
    {
        return $this->getDepartmentInstructors('IS', 'is_instructor');
    }

    public function csIndex()
    {
        return $this->getDepartmentInstructors('CS', 'cs_instructor');
    }

    public function aiIndex()
    {
        return $this->getDepartmentInstructors('AI', 'ai_instructor');
    }

    public function bioIndex()
    {
        return $this->getDepartmentInstructors('BIO', 'bio_instructor');
    }
}
