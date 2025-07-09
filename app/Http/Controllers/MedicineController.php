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
        $med = Medicine::create($req->validated() + ['created_by'=>auth()->id()]);
        return new MedicineResource($med->load('category','creator'));
    }

    public function show(Medicine $medicine) {
        return new MedicineResource($medicine->load('category','creator'));
    }

    public function update(UpdateMedicineRequest $req, Medicine $medicine) {
        $medicine->update($req->validated());
        return new MedicineResource($medicine->fresh()->load('category','creator'));
    }

    public function destroy(Medicine $medicine) {
        $medicine->delete();
        return response()->noContent();
    }
}
