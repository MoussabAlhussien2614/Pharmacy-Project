<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use App\Http\Resources\MedicineResource;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
     public function index() {
        return MedicineResource::collection(Medicine::with('category','creator')->get());
    }

    public function store(CreateMedicineRequest $req) {
        $data = $req->validated();
        $data['price'] = $data['price'] * 1.10; // Add 10%
        $data['created_by'] = auth()->id();

        $med = Medicine::create($data);
        return new MedicineResource($med->load('category', 'creator'));
    }


    public function show(Medicine $medicine) {
        return new MedicineResource($medicine->load('category','creator'));
    }

    public function update(UpdateMedicineRequest $req, Medicine $medicine) {
        $data = $req->validated();

        if (isset($data['price'])) {
            $data['price'] = $data['price'] * 1.10; // Add 10%
        }

        $medicine->update($data);
        return new MedicineResource($medicine->fresh()->load('category', 'creator'));
    }


    public function destroy(Medicine $medicine) {
        $medicine->delete();
        return response()->noContent();
    }

    public function addQuantity(Request $request, $id)
    {
        $request->validate([
            'new_qty' => 'required|integer|min:1',
        ]);

        $medicine = Medicine::findOrFail($id);

        $medicine->quantity += $request->input('new_qty');
        $medicine->save();

        return response()->json([
            'message' => 'Quantity updated successfully.',
            'medicine' => new MedicineResource($medicine->fresh()->load('category','creator')),
        ]);
    }


    public function search(Request $request)
    {
        $request->validate([
                'type' => 'required|in:name,company,category',
                'query' => 'required|string',
            ]);
        $query = $request->query('query');
        $type = $request->query('type');

        $medicines = Medicine::query();

        if ($type === 'category') {
            $medicines->whereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%$query%");
            });
        } else {
            $medicines->where($type, 'like', "%$query%");
        }

        return MedicineResource::collection($medicines->with('category', 'creator')->get());
    }

}
