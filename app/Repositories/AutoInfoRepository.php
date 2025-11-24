<?php

namespace App\Repositories;

use App\Models\BodyType;
use App\Models\CarMark;
use App\Models\Engine;
use App\Models\FuelType;
use App\Models\PartCategory;
use Illuminate\Database\Eloquent\Collection;

class AutoInfoRepository
{
    /**
     * Gauti automobilio markes su jų modeliais.
     */
    public function getCarMarksWithModels(): Collection
    {
        return CarMark::query()
            ->with(['models' => function ($query) {
                $query->orderBy('title');
            }])
            ->orderBy('title')
            ->get(['id', 'title']);
    }

    /**
     * Gauti detalių kategorijas (tvarka: tėvai -> vaikai).
     */
    public function getPartCategories(): Collection
    {
        return PartCategory::query()
            ->orderByRaw('parent_id IS NULL DESC')
            ->orderBy('parent_id')
            ->orderBy('title')
            ->get(['id', 'title', 'parent_id']);
    }

    /**
     * Gauti kuro tipus.
     */
    public function getFuelTypes(): Collection
    {
        return FuelType::query()
            ->orderBy('title')
            ->get(['id', 'title']);
    }

    /**
     * Gauti kėbulo tipus.
     */
    public function getBodyTypes(): Collection
    {
        return BodyType::query()
            ->orderBy('title')
            ->get(['id', 'title']);
    }

    /**
     * Gauti variklių tipus.
     */
    public function getEngines(): Collection
    {
        return Engine::query()
            ->orderBy('title')
            ->get(['id', 'title']);
    }
}

