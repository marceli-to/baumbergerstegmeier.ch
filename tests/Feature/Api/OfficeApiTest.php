<?php

namespace Tests\Feature\Api;

use App\Models\Cv;
use App\Models\CvCategory;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\Team;

/**
 * Employees (list keyed by category) and their CV entries
 */
class OfficeApiTest extends ApiTestCase
{
    private Team $team;
    private array $categories = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->team = Team::forceCreate(['slug' => 'bs', 'name' => 'BS', 'order' => 1, 'publish' => 1]);
        // The list is keyed by the category ids 1, 2 and 3
        foreach (['partner' => 'Partner', 'mitarbeitende' => 'Mitarbeitende', 'ehemalige' => 'Ehemalige'] as $slug => $name) {
            $this->categories[] = EmployeeCategory::forceCreate(['slug' => $slug, 'name' => $name, 'publish' => 1])->id;
        }
    }

    private function employeePayload(array $data = []): array
    {
        return array_merge([
            'firstname' => 'Erika',
            'name' => 'Muster',
            'title' => 'Architektin',
            'email' => 'e@example.invalid',
            'phone' => '+41 44 000 00 00',
            'publish' => 1,
            'team_id' => $this->team->id,
            'employee_category_id' => $this->categories[0],
        ], $data);
    }

    private function employee(array $data = []): Employee
    {
        return Employee::forceCreate(array_merge([
            'firstname' => 'Max', 'name' => 'Beispiel', 'order' => 1, 'publish' => 1,
            'team_id' => $this->team->id, 'employee_category_id' => $this->categories[0],
        ], $data));
    }

    public function testEmployeeStore()
    {
        $id = $this->postJson('/api/employee', $this->employeePayload())->assertOk()->json('employeeId');

        $employee = Employee::findOrFail($id);
        $this->assertSame('Erika', $employee->firstname);
        $this->assertSame('+41 44 000 00 00', $employee->phone);
        $this->assertEquals($this->team->id, $employee->team_id);
        $this->assertEquals($this->categories[0], $employee->employee_category_id);
        $this->assertEquals(1, $employee->publish);
    }

    public function testEmployeeStoreValidatesRelations()
    {
        $this->postJson('/api/employee', $this->employeePayload(['team_id' => 999, 'employee_category_id' => 999]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['team_id', 'employee_category_id']);
        $this->assertSame(0, Employee::count());
    }

    public function testEmployeeListIsKeyedByCategory()
    {
        $partnerB = $this->employee(['name' => 'B', 'order' => 2]);
        $partnerA = $this->employee(['name' => 'A', 'order' => 1]);
        $staffZ = $this->employee(['name' => 'Zeller', 'employee_category_id' => $this->categories[1]]);
        $staffA = $this->employee(['name' => 'Ammann', 'employee_category_id' => $this->categories[1]]);
        $former = $this->employee(['name' => 'Früher', 'employee_category_id' => $this->categories[2]]);

        $this->getJson('/api/employees')
            ->assertOk()
            ->assertJsonStructure(['leadership', 'employees', 'former_employees'])
            // Leadership by order, the others by name
            ->assertJsonPath('leadership.0.id', $partnerA->id)
            ->assertJsonPath('leadership.1.id', $partnerB->id)
            ->assertJsonPath('leadership.0.team.name', 'BS')
            ->assertJsonPath('leadership.0.employee_category.name', 'Partner')
            ->assertJsonPath('employees.0.id', $staffA->id)
            ->assertJsonPath('employees.1.id', $staffZ->id)
            ->assertJsonPath('former_employees.0.id', $former->id)
            ->assertJsonCount(1, 'former_employees');
    }

    public function testEmployeeFind()
    {
        $employee = $this->employee();

        $this->getJson("/api/employee/{$employee->id}")
            ->assertOk()
            ->assertJsonPath('employee.id', $employee->id)
            ->assertJsonPath('employee.team.id', $this->team->id)
            ->assertJsonPath('employee.employee_category.id', $this->categories[0])
            ->assertJsonCount(3, 'employeeCategories')
            ->assertJsonCount(1, 'teams');
    }

    public function testEmployeeUpdateToggleDestroy()
    {
        $employee = $this->employee();

        $this->putJson("/api/employee/{$employee->id}", $this->employeePayload(['firstname' => 'Eva', 'publish' => 0, 'employee_category_id' => $this->categories[2]]))
            ->assertOk()
            ->assertExactJson(['successfully updated']);
        $fresh = $employee->fresh();
        $this->assertSame('Eva', $fresh->firstname);
        $this->assertSame('Muster', $fresh->name);
        $this->assertEquals($this->categories[2], $fresh->employee_category_id);
        $this->assertEquals(0, $fresh->publish);

        $this->getJson("/api/employee/state/{$employee->id}")->assertOk()->assertContent('true');
        $this->assertEquals(1, $employee->fresh()->publish);

        $this->deleteJson("/api/employee/{$employee->id}")->assertOk()->assertExactJson(['successfully deleted']);
        $this->assertNull(Employee::find($employee->id));
    }

    public function testEmployeeOrder()
    {
        $a = $this->employee(['name' => 'A', 'order' => 1]);
        $b = $this->employee(['name' => 'B', 'order' => 2]);

        $this->postJson('/api/employee/order', ['employees' => [['id' => $b->id, 'order' => 0], ['id' => $a->id, 'order' => 1]]])
            ->assertOk()
            ->assertExactJson(['successfully updated']);

        $this->assertEquals(0, $b->fresh()->order);
        $this->assertEquals(1, $a->fresh()->order);
        $this->getJson('/api/employees')->assertJsonPath('leadership.0.id', $b->id);
    }

    public function testCvStoreAndFind()
    {
        $employee = $this->employee();
        $category = CvCategory::forceCreate(['description' => 'Ausbildung', 'order' => 1, 'publish' => 1]);

        $id = $this->postJson('/api/cv', ['periode' => '2000', 'description' => 'Diplom', 'employee_id' => $employee->id, 'cv_category_id' => $category->id, 'publish' => 1])
            ->assertOk()
            ->json('cvId');

        $cv = Cv::findOrFail($id);
        $this->assertSame('Diplom', $cv->description);
        $this->assertEquals($employee->id, $cv->employee_id);
        $this->assertEquals($category->id, $cv->cv_category_id);

        $this->getJson("/api/cvs/{$id}")
            ->assertOk()
            ->assertJsonPath('cv.id', $id)
            ->assertJsonPath('cv.category.description', 'Ausbildung')
            ->assertJsonPath('categories.0.id', $category->id);

        $this->postJson('/api/cv', ['periode' => '2000', 'description' => 'X', 'employee_id' => 999, 'publish' => 1])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['employee_id']);
    }

    public function testCvListGroupsByCategory()
    {
        $employee = $this->employee();
        $other = $this->employee(['name' => 'Andere']);
        $education = CvCategory::forceCreate(['description' => 'Ausbildung', 'order' => 1, 'publish' => 1]);
        $teaching = CvCategory::forceCreate(['description' => 'Lehre', 'order' => 2, 'publish' => 1]);

        $plain = Cv::forceCreate(['periode' => '1990', 'description' => 'Ohne', 'order' => 1, 'publish' => 1, 'employee_id' => $employee->id]);
        $second = Cv::forceCreate(['periode' => '2001', 'description' => 'Zwei', 'order' => 2, 'publish' => 1, 'employee_id' => $employee->id, 'cv_category_id' => $education->id]);
        $first = Cv::forceCreate(['periode' => '2000', 'description' => 'Eins', 'order' => 1, 'publish' => 1, 'employee_id' => $employee->id, 'cv_category_id' => $education->id]);
        $taught = Cv::forceCreate(['periode' => '2010', 'description' => 'ETH', 'order' => 1, 'publish' => 1, 'employee_id' => $employee->id, 'cv_category_id' => $teaching->id]);
        Cv::forceCreate(['periode' => '2010', 'description' => 'Fremd', 'order' => 1, 'publish' => 1, 'employee_id' => $other->id]);

        $this->getJson("/api/cv/{$employee->id}")
            ->assertOk()
            ->assertJsonCount(1, 'cv')
            ->assertJsonPath('cv.0.id', $plain->id)
            ->assertJsonPath("cvCategorized.{$education->id}.0.id", $first->id)
            ->assertJsonPath("cvCategorized.{$education->id}.1.id", $second->id)
            ->assertJsonPath("cvCategorized.{$education->id}.0.category.description", 'Ausbildung')
            ->assertJsonPath("cvCategorized.{$teaching->id}.0.id", $taught->id);
    }

    public function testCvUpdateToggleOrderDestroy()
    {
        $employee = $this->employee();
        $other = $this->employee(['name' => 'Andere']);
        $a = Cv::forceCreate(['periode' => '2000', 'description' => 'A', 'order' => 1, 'publish' => 1, 'employee_id' => $employee->id]);
        $b = Cv::forceCreate(['periode' => '2001', 'description' => 'B', 'order' => 2, 'publish' => 1, 'employee_id' => $employee->id]);

        // employee_id is validated but never reassigned
        $this->putJson("/api/cv/{$a->id}", ['periode' => '1999', 'description' => 'A2', 'employee_id' => $other->id, 'cv_category_id' => null, 'publish' => 0])
            ->assertOk()
            ->assertExactJson(['successfully updated']);
        $fresh = $a->fresh();
        $this->assertSame('1999', $fresh->periode);
        $this->assertSame('A2', $fresh->description);
        $this->assertEquals($employee->id, $fresh->employee_id);
        $this->assertEquals(0, $fresh->publish);

        $this->getJson("/api/cv/state/{$a->id}")->assertOk()->assertContent('true');
        $this->assertEquals(1, $a->fresh()->publish);

        $this->postJson('/api/cv/order', ['cvs' => [['id' => $b->id, 'order' => 0], ['id' => $a->id, 'order' => 1]]])
            ->assertOk()
            ->assertExactJson(['successfully updated']);
        $this->assertEquals(0, $b->fresh()->order);
        $this->assertEquals(1, $a->fresh()->order);
        $this->getJson("/api/cv/{$employee->id}")->assertJsonPath('cv.0.id', $b->id);

        $this->deleteJson("/api/cv/{$a->id}")->assertOk()->assertExactJson(['successfully deleted']);
        $this->assertNull(Cv::find($a->id));
    }
}
