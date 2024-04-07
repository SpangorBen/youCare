<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      title="YouCare",
 *      version="1.0.0",
 *      description="YouCare API is a platform designed to facilitate volunteerism by connecting organizers of events with individuals willing to contribute their time and skills. Through this API, organizers can create announcements for various initiatives, specifying details such as event type, description, date, location, and required skills. Volunteers can browse these announcements, filtering them based on event type or location, and apply to participate in projects aligning with their interests and availability. The API also supports authentication mechanisms, allowing secure access to routes requiring authentication. Additionally, organizers have the ability to rate volunteers after each event, providing feedback to the community. Administrators can manage organizers, announcements, and volunteers, as well as view statistics related to events, organizers, and volunteers. With comprehensive documentation provided through Swagger, utilizing the YouCare API is made straightforward and accessible.",
 *      @OA\Contact(
 *          email="test@test.com"
 *      ),
 *      @OA\License(
 *          name="API License",
 *          url="http://www.example.com/license"
 *      ),
 * )
 * @OA\SecurityScheme(
 *
 *   type="http",
 *   securityScheme="bearerAuth",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 */

class AnnonceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/annonce",
     *     summary="Get a list of annonces",
     *     tags={"Annonces"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        // $annonces = Annonce::where('user_id', Auth::user()->id)->get();
        $annonces = Annonce::all();
        return response()->json(['data' => $annonces]);
    }
    /**
     * @OA\Get(
     *     path="/annonce",
     *     summary="Show one annonce",
     *     tags={"Annonces"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function show($id)
    {
        $annonce = Annonce::findOrFail($id);
        return response()->json(['data' => $annonce]);
    }

    /**
     * @OA\Post(
     *     path="/api/annonce",
     *     summary="Store annonce in database",
     *     tags={"Annonces"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string',
            'required_skills' => 'required|string',
            // 'organizer_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:types,id',
        ]);

        $request['organizer_id'] = auth()->user()->id;

        $annonce = Annonce::create($request->all());

        return response()->json(['message' => 'Annonce created successfully', 'data' => $annonce], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/annonce",
     *     summary="Update annonce in database",
     *     tags={"Annonces"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function update(Request $request, $id)
    {
        $annonce = Annonce::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string',
            'required_skills' => 'required|string',
            // 'organizer_id' => 'required',y   
            'type_id' => 'required|exists:types,id',
        ]);

        // $request['organizer_id'] = auth()->user()->id;
        if ($annonce->organizer_id === auth()->user()->id) {
            $annonce->update($request->all());
        } else{
            return response()->json(['message' => 'You are not allowed']);
        }

        return response()->json(['message' => 'Annonce updated successfully', 'data' => $annonce]);
    }

    /**
     * @OA\Delete(
     *     path="/api/annonce",
     *     summary="Delete annonce",
     *     tags={"Annonces"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);
        $user_id = $annonce->organizer_id;
        $annonce->delete();

        return response()->json(['message' => 'Annonce deleted successfully'], 204);
    }

    /**
     * @OA\Get(
     *     path="/api/annonces/filter",
     *     summary="Filter annonces by type and also location",
     *     tags={"Annonces"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function filter(Request $request)
    {
        $request->validate([
            'type_id' => 'integer|nullable',
            'location' => 'string|nullable',
        ]);

        $query = Annonce::query();

        $query->where(function ($query) use ($request) {
            if ($request->has('type_id')) {
                $query->where('type_id', $request->type_id);
            }

            if ($request->has('location')) {
                $query->Where('location', $request->location);
            }
        });

        $annonces = $query->get();

        return response()->json(['data' => $annonces]);
    }
}
