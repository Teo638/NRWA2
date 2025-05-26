<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Resources\FacultyResource;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Profesori",
 *     description="API Endpoints za upravljanje profesorima (nastavnim osobljem)"
 * )
 */
class FacultyController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/faculty",
     *      operationId="getFacultyList",
     *      tags={"Profesori"},
     *      summary="Dohvati listu profesora",
     *      description="Vraća paginiranu listu svog nastavnog osoblja.",
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Broj stranice za paginaciju",
     *          required=false,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Uspješno dohvaćanje profesora",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="first_name", type="string", example="Pero"),
     *                  @OA\Property(property="last_name", type="string", example="Perić"),
     *                  @OA\Property(property="department_id", type="integer", example=1),
     *                  @OA\Property(property="phone", type="string", nullable=true, example="061123456"),
     *                  @OA\Property(property="department", type="object", nullable=true,
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Računarstvo")
     *                  ),
     *                  @OA\Property(property="is_hod_of_department", type="object", nullable=true,
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Računarstvo")
     *                  )
     *              )),
     *              @OA\Property(property="links", type="object"),
     *              @OA\Property(property="meta", type="object")
     *          )
     *      )
     * )
     */
    public function index()
    {
        return FacultyResource::collection(Faculty::with(['department', 'departmentHeaded'])->paginate(15));
    }

    /**
     * @OA\Post(
     *      path="/api/faculty",
     *      operationId="storeFaculty",
     *      tags={"Profesori"},
     *      summary="Kreiraj novog profesora",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Podaci za novog profesora",
     *          @OA\JsonContent(
     *              required={"first_name", "last_name", "department_id"},
     *              @OA\Property(property="first_name", type="string", maxLength=25, example="Novi"),
     *              @OA\Property(property="last_name", type="string", maxLength=25, example="Profesor"),
     *              @OA\Property(property="department_id", type="integer", description="ID postojećeg odjela", example=1),
     *              @OA\Property(property="phone", type="string", maxLength=10, nullable=true, example="062333444")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Profesor uspješno kreiran",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=10),
     *              @OA\Property(property="first_name", type="string", example="Novi"),
     *              @OA\Property(property="last_name", type="string", example="Profesor"),
     *              @OA\Property(property="department_id", type="integer", example=1),
     *              @OA\Property(property="phone", type="string", nullable=true, example="062333444"),
     *              @OA\Property(property="department", type="object", nullable=true,
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Računarstvo")
     *              ),
     *              @OA\Property(property="is_hod_of_department", type="object", nullable=true)
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
            'department_id' => 'required|integer|exists:departments,id',
            'phone' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $faculty = Faculty::create($validator->validated());
        return new FacultyResource($faculty->load(['department', 'departmentHeaded']));
    }

    /**
     * @OA\Get(
     *      path="/api/faculty/{faculty_id}",
     *      operationId="getFacultyById",
     *      tags={"Profesori"},
     *      summary="Dohvati profesora po ID-u",
     *      @OA\Parameter(
     *          name="faculty_id",
     *          in="path",
     *          required=true,
     *          description="ID profesora",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Uspješno dohvaćanje profesora",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="first_name", type="string", example="Pero"),
     *              @OA\Property(property="last_name", type="string", example="Perić"),
     *              @OA\Property(property="department_id", type="integer", example=1),
     *              @OA\Property(property="phone", type="string", nullable=true, example="061123456"),
     *              @OA\Property(property="department", type="object", nullable=true,
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Računarstvo")
     *              ),
     *              @OA\Property(property="is_hod_of_department", type="object", nullable=true,
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Računarstvo")
     *              ),
     *              @OA\Property(property="subjects", type="array", @OA\Items(type="object"), description="Lista predmeta koje profesor predaje (ako je učitana)")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Profesor nije pronađen"
     *      )
     * )
     */
    public function show(Faculty $faculty)
    {
        return new FacultyResource($faculty->load(['department', 'departmentHeaded', 'subjects']));
    }

    /**
     * @OA\Put(
     *      path="/api/faculty/{faculty_id}",
     *      operationId="updateFaculty",
     *      tags={"Profesori"},
     *      summary="Ažuriraj profesora",
     *      @OA\Parameter(
     *          name="faculty_id",
     *          in="path",
     *          required=true,
     *          description="ID profesora za ažuriranje",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Podaci za ažuriranje profesora",
     *          @OA\JsonContent(
     *              @OA\Property(property="first_name", type="string", maxLength=25, example="Ažurirani Pero"),
     *              @OA\Property(property="last_name", type="string", maxLength=25, example="Ažurirani Perić"),
     *              @OA\Property(property="department_id", type="integer", description="ID postojećeg odjela", example=1),
     *              @OA\Property(property="phone", type="string", maxLength=10, nullable=true, example="060555666")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Profesor uspješno ažuriran",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="first_name", type="string", example="Ažurirani Pero"),
     *              @OA\Property(property="last_name", type="string", example="Ažurirani Perić"),
     *              @OA\Property(property="department_id", type="integer", example=1),
     *              @OA\Property(property="phone", type="string", nullable=true, example="060555666"),
     *              @OA\Property(property="department", type="object", nullable=true),
     *              @OA\Property(property="is_hod_of_department", type="object", nullable=true)
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Profesor nije pronađen"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Greška validacije"
     *      )
     * )
     */
    public function update(Request $request, Faculty $faculty)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:25',
            'last_name' => 'sometimes|required|string|max:25',
            'department_id' => 'sometimes|required|integer|exists:departments,id',
            'phone' => 'sometimes|nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $faculty->update($validator->validated());
        return new FacultyResource($faculty->load(['department', 'departmentHeaded']));
    }

    /**
     * @OA\Delete(
     *      path="/api/faculty/{faculty_id}",
     *      operationId="deleteFaculty",
     *      tags={"Profesori"},
     *      summary="Obriši profesora",
     *      @OA\Parameter(
     *          name="faculty_id",
     *          in="path",
     *          required=true,
     *          description="ID profesora za brisanje",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Profesor uspješno obrisan"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Profesor nije pronađen"
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Konflikt pri brisanju (npr. profesor je HOD ili povezan s predmetima)",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="error_details", type="string", nullable=true)
     *          )
     *      )
     * )
     */
    public function destroy(Faculty $faculty)
    {
        try {
            if ($faculty->departmentHeaded()->exists()) {
                return response()->json([
                    'message' => 'Cannot delete faculty. This faculty member is HOD of a department. Please assign a new HOD or set HOD to null for that department first.',
                ], 409);
            }

            $faculty->delete();
            return response()->json(null, 204);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return response()->json([
                    'message' => 'Cannot delete faculty. It might be associated with subjects or other records.',
                    'error_details' => $e->getMessage()
                ], 409);
            }
            return response()->json([
                'message' => 'Error deleting faculty.',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }
}