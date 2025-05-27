<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Resources\DepartmentResource;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Odjeli",
 *     description="API Endpoints za upravljanje odjelima"
 * )
 */
class DepartmentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/departments",
     *      operationId="getDepartmentsList",
     *      tags={"Odjeli"},
     *      summary="Dohvati listu odjela",
     *      description="Vraća paginiranu listu svih odjela.",
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Broj stranice za paginaciju",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Uspješno dohvaćanje odjela",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Računarstvo"),
     *                  @OA\Property(property="hod_id", type="integer", nullable=true, example=1),
     *                  @OA\Property(property="head_of_department", type="object", nullable=true,
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="first_name", type="string", example="Pero"),
     *                      @OA\Property(property="last_name", type="string", example="Perić")
     *                  )
     *              )),
     *              @OA\Property(property="links", type="object"),
     *              @OA\Property(property="meta", type="object")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Neautoriziran pristup / Potrebna autentifikacija.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      )
     * )
     */
    public function index()
    {
        return DepartmentResource::collection(Department::with('hod')->paginate(10));
    }

    /**
     * @OA\Post(
     *      path="/api/departments",
     *      operationId="storeDepartment",
     *      tags={"Odjeli"},
     *      summary="Kreiraj novi odjel",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Podaci za novi odjel",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", maxLength=30, example="Novi Odjel"),
     *              @OA\Property(property="hod_id", type="integer", nullable=true, description="ID postojećeg profesora (faculty) kao HOD-a", example=1)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Odjel uspješno kreiran",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=10),
     *              @OA\Property(property="name", type="string", example="Novi Odjel"),
     *              @OA\Property(property="hod_id", type="integer", nullable=true, example=1),
     *              @OA\Property(property="head_of_department", type="object", nullable=true,
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="first_name", type="string", example="Pero"),
     *                  @OA\Property(property="last_name", type="string", example="Perić")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Greška validacije",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Neautoriziran pristup / Potrebna autentifikacija.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30|unique:departments,name',
            'hod_id' => 'nullable|integer|exists:faculty,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $department = Department::create($validator->validated());
        return new DepartmentResource($department->load('hod'));
    }

    /**
     * @OA\Get(
     *      path="/api/departments/{department_id}",
     *      operationId="getDepartmentById",
     *      tags={"Odjeli"},
     *      summary="Dohvati odjel po ID-u",
     *      @OA\Parameter(
     *          name="department_id",
     *          in="path",
     *          required=true,
     *          description="ID odjela",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Uspješno dohvaćanje odjela",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="Računarstvo"),
     *              @OA\Property(property="hod_id", type="integer", nullable=true, example=1),
     *              @OA\Property(property="head_of_department", type="object", nullable=true,
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="first_name", type="string", example="Pero"),
     *                  @OA\Property(property="last_name", type="string", example="Perić")
     *              ),
     *              @OA\Property(property="students", type="array", @OA\Items(type="object"), description="Lista studenata na odjelu (ako je učitana)"),
     *              @OA\Property(property="faculty_members", type="array", @OA\Items(type="object"), description="Lista profesora na odjelu (ako je učitana)")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Odjel nije pronađen",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Department] 99")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Neautoriziran pristup / Potrebna autentifikacija.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      )
     * )
     */
    public function show(Department $department)
    {
        return new DepartmentResource($department->load(['hod', 'students', 'facultyMembers']));
    }

    /**
     * @OA\Put(
     *      path="/api/departments/{department_id}",
     *      operationId="updateDepartment",
     *      tags={"Odjeli"},
     *      summary="Ažuriraj odjel",
     *      @OA\Parameter(
     *          name="department_id",
     *          in="path",
     *          required=true,
     *          description="ID odjela za ažuriranje",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Podaci za ažuriranje odjela",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", maxLength=30, example="Ažurirani Odjel"),
     *              @OA\Property(property="hod_id", type="integer", nullable=true, description="ID postojećeg profesora (faculty) kao HOD-a", example=2)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Odjel uspješno ažuriran",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="Ažurirani Odjel"),
     *              @OA\Property(property="hod_id", type="integer", nullable=true, example=2),
     *              @OA\Property(property="head_of_department", type="object", nullable=true,
     *                  @OA\Property(property="id", type="integer", example=2),
     *                  @OA\Property(property="first_name", type="string", example="Ana"),
     *                  @OA\Property(property="last_name", type="string", example="Anić")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Odjel nije pronađen"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Greška validacije"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Neautoriziran pristup / Potrebna autentifikacija.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      )
     * )
     */
    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:30|unique:departments,name,' . $department->id,
            'hod_id' => 'sometimes|nullable|integer|exists:faculty,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $department->update($validator->validated());
        return new DepartmentResource($department->load('hod'));
    }

    /**
     * @OA\Delete(
     *      path="/api/departments/{department_id}",
     *      operationId="deleteDepartment",
     *      tags={"Odjeli"},
     *      summary="Obriši odjel",
     *      @OA\Parameter(
     *          name="department_id",
     *          in="path",
     *          required=true,
     *          description="ID odjela za brisanje",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Odjel uspješno obrisan"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Odjel nije pronađen"
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Konflikt pri brisanju (npr. odjel je povezan s drugim zapisima)",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="error_details", type="string", nullable=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Neautoriziran pristup / Potrebna autentifikacija.",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      )
     * )
     */
    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return response()->json(null, 204);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return response()->json([
                    'message' => 'Cannot delete department. It is referenced by other records (e.g., faculty, students, or subjects).',
                    'error_details' => $e->getMessage()
                ], 409);
            }
            return response()->json([
                'message' => 'Error deleting department.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }
}