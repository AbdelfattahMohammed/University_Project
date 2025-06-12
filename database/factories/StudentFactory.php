<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('ar_EG');

        $departmentIds = Department::pluck('id')->toArray();

        if (empty($departmentIds)) {
            $departmentIds = ['CS', 'IS', 'AI', 'BIO'];
        }

        $gender = $faker->randomElement(['Male', 'Female']);

        // توليد اسم بدون ألقاب: نستخدم firstName و lastName
        // ثم ندمجهما.
        if ($gender === 'Male') {
            $firstName = $faker->firstNameMale();
            $lastName = $faker->lastName();
            $name = $firstName . ' ' . $lastName;
        } else {
            $firstName = $faker->firstNameFemale();
            $lastName = $faker->lastName();
            $name = $firstName . ' ' . $lastName;
        }

        $phone = '01' . $faker->randomElement(['0', '1', '2', '5']) . $faker->unique()->numerify('########');

        $universityYear = $faker->numberBetween(1, 4);

        $address = $faker->address();

        // **تعديل الحضور والغياب:**
        // توليد الحضور بشكل عشوائي بين 0 و 100
        $attendance = $faker->numberBetween(0, 100);
        // الغياب هو المكمل للحضور حتى 100
        $absence = 100 - $attendance;

        return [
            'name' => $name, // استخدام الاسم الذي تم توليده بدون ألقاب
            'gender' => $gender,
            'year' => $universityYear,
            'department_id' => $faker->randomElement($departmentIds),
            'gpa' => $faker->randomFloat(2, 2.0, 4.0),
            'attendance' => $attendance, // قيمة حضور عشوائية
            'absence' => $absence,       // قيمة غياب مكملة لها
            'address' => $address,
            'phone' => $phone,
        ];
    }
}
