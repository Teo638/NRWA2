<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="College API Documentation",
 *      description="API sučelje za upravljanje studentima, odjelima i profesorima u sustavu fakulteta."
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Lokalni razvojni server"
 * )
 *
 * @OA\Tag(
 *     name="Studenti",
 *     description="API Endpoints za upravljanje studentima"
 * )
 */

class StudentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/students",
     *      operationId="getStudentsList",
     *      tags={"Studenti"},
     *      summary="Dohvati listu studenata",
     *      description="Vraća paginiranu listu svih studenata.",
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Broj stranice za paginaciju",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Uspješno dohvaćanje studenata",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="roll_number", type="integer", format="int64", example=170101),
     *                  @OA\Property(property="first_name", type="string", example="Marko"),
     *                  @OA\Property(property="last_name", type="string", example="Marković"),
     *                  @OA\Property(property="department_id", type="integer", nullable=true, example=1),
     *                  @OA\Property(property="phone", type="string", nullable=true, example="063123456"),
     *                  @OA\Property(property="admission_date", type="string", format="date", example="2023-09-01"),
     *                  @OA\Property(property="cet_marks", type="integer", example=120),
     *                  @OA\Property(property="department", type="object", nullable=true,
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Computer")
     *                  )
     *              )),
     *              @OA\Property(property="links", type="object",
     *                  @OA\Property(property="first", type="string", example="http://localhost/api/students?page=1"),
     *                  @OA\Property(property="last", type="string", example="http://localhost/api/students?page=5"),
     *                  @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                  @OA\Property(property="next", type="string", nullable=true, example="http://localhost/api/students?page=2")
     *              ),
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="current_page", type="integer", example=1),
     *                  @OA\Property(property="from", type="integer", example=1),
     *                  @OA\Property(property="last_page", type="integer", example=5),
     *                  @OA\Property(property="path", type="string", example="http://localhost/api/students"),
     *                  @OA\Property(property="per_page", type="integer", example=15),
     *                  @OA\Property(property="to", type="integer", example=15),
     *                  @OA\Property(property="total", type="integer", example=75)
     *              )
     *          )
     *      )
     * )
     */
    public function index()
    {
        return StudentResource::collection(Student::with('department')->paginate(15));
    }

    /**
     * @OA\Post(
     *      path="/api/students",
     *      operationId="storeStudent",
     *      tags={"Studenti"},
     *      summary="Kreiraj novog studenta",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Podaci za novog studenta",
     *          @OA\JsonContent(
     *              required={"first_name", "last_name", "admission_date", "cet_marks"},
     *              @OA\Property(property="first_name", type="string", maxLength=25, example="Ana"),
     *              @OA\Property(property="last_name", type="string", maxLength=25, example="Anić"),
     *              @OA\Property(property="department_id", type="integer", nullable=true, description="ID postojećeg odjela", example=1),
     *              @OA\Property(property="phone", type="string", maxLength=10, nullable=true, example="099123456"),
     *              @OA\Property(property="admission_date", type="string", format="date", description="YYYY-MM-DD", example="2024-01-15"),
     *              @OA\Property(property="cet_marks", type="integer", example=135)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Student uspješno kreiran",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="roll_number", type="integer", format="int64", description="Automatski generiran roll_number"),
     *              @OA\Property(property="first_name", type="string", example="Ana"),
     *              @OA\Property(property="last_name", type="string", example="Anić"),
     *              @OA\Property(property="department_id", type="integer", nullable=true, example=1),
     *              @OA\Property(property="phone", type="string", nullable=true, example="099123456"),
     *              @OA\Property(property="admission_date", type="string", format="date", example="2024-01-15"),
     *              @OA\Property(property="cet_marks", type="integer", example=135),
     *              @OA\Property(property="department", type="object", nullable=true,
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Computer")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Greška validacije",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"first_name": {"The first name field is required."}})
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'department_id' => 'nullable|integer|exists:departments,id',
            'phone' => 'nullable|string|max:10',
            'admission_date' => 'required|date_format:Y-m-d',
            'cet_marks' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $student = Student::create($validator->validated());
        return new StudentResource($student->load('department'));
    }

    /**
     * @OA\Get(
     *      path="/api/students/{roll_num}",
     *      operationId="getStudentByRollNum",
     *      tags={"Studenti"},
     *      summary="Dohvati studenta po roll broju",
     *      @OA\Parameter(
     *          name="roll_num",
     *          in="path",
     *          required=true,
     *          description="Roll broj studenta",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Uspješno dohvaćanje studenta",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="roll_number", type="integer", format="int64", example=170101),
     *              @OA\Property(property="first_name", type="string", example="Marko"),
     *              @OA\Property(property="last_name", type="string", example="Marković"),
     *              @OA\Property(property="department_id", type="integer", nullable=true, example=1),
     *              @OA\Property(property="phone", type="string", nullable=true, example="063123456"),
     *              @OA\Property(property="admission_date", type="string", format="date", example="2023-09-01"),
     *              @OA\Property(property="cet_marks", type="integer", example=120),
     *              @OA\Property(property="department", type="object", nullable=true,
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Computer")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Student nije pronađen",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Student] 99999")
     *          )
     *      )
     * )
     */
    public function show($roll_num)
    {
        $student = Student::with('department')->findOrFail($roll_num);
        return new StudentResource($student);
    }

    /**
     * @OA\Put(
     *      path="/api/students/{roll_num}",
     *      operationId="updateStudent",
     *      tags={"Studenti"},
     *      summary="Ažuriraj studenta",
     *      @OA\Parameter(
     *          name="roll_num",
     *          in="path",
     *          required=true,
     *          description="Roll broj studenta za ažuriranje",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Podaci za ažuriranje studenta",
     *          @OA\JsonContent(
     *              @OA\Property(property="first_name", type="string", maxLength=25, example="Ana Marija"),
     *              @OA\Property(property="last_name", type="string", maxLength=25, example="Anić Popović"),
     *              @OA\Property(property="department_id", type="integer", nullable=true, description="ID postojećeg odjela", example=1),
     *              @OA\Property(property="phone", type="string", maxLength=10, nullable=true, example="099654321"),
     *              @OA\Property(property="admission_date", type="string", format="date", description="YYYY-MM-DD", example="2024-01-16"),
     *              @OA\Property(property="cet_marks", type="integer", example=140)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Student uspješno ažuriran",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="roll_number", type="integer", format="int64"),
     *              @OA\Property(property="first_name", type="string"),
     *              @OA\Property(property="last_name", type="string"),
     *              @OA\Property(property="department_id", type="integer", nullable=true),
     *              @OA\Property(property="phone", type="string", nullable=true),
     *              @OA\Property(property="admission_date", type="string", format="date"),
     *              @OA\Property(property="cet_marks", type="integer"),
     *              @OA\Property(property="department", type="object", nullable=true,
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="name", type="string")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Student nije pronađen",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Student] 99999")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Greška validacije",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"field_name": {"Error message for field_name."}})
     *          )
     *      )
     * )
     */
    public function update(Request $request, $roll_num)
    {
        $student = Student::findOrFail($roll_num);

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:25',
            'last_name' => 'sometimes|required|string|max:25',
            'department_id' => 'sometimes|nullable|integer|exists:departments,id',
            'phone' => 'sometimes|nullable|string|max:10',
            'admission_date' => 'sometimes|required|date_format:Y-m-d',
            'cet_marks' => 'sometimes|required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $student->update($validator->validated());
        return new StudentResource($student->load('department'));
    }

    /**
     * @OA\Delete(
     *      path="/api/students/{roll_num}",
     *      operationId="deleteStudent",
     *      tags={"Studenti"},
     *      summary="Obriši studenta",
     *      @OA\Parameter(
     *          name="roll_num",
     *          in="path",
     *          required=true,
     *          description="Roll broj studenta za brisanje",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Student uspješno obrisan (Nema sadržaja u odgovoru)"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Student nije pronađen",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Student] 99999")
     *          )
     *      )
     * )
     */
    public function destroy($roll_num)
    {
        $student = Student::findOrFail($roll_num);
        $student->delete();
        return response()->json(null, 204);
    }
}