<?php

namespace App\Modules\Condition\Services;

use App\Modules\Condition\Requests\ConditionRequest;
use App\Modules\Condition\Models\Condition;

class ConditionService
{
    public function all()
    {
        return Condition::all();
    }

    public function delete($id)
    {
        $condition = Condition::find($id);
        if ($condition->delete()) {
            return ['status' => 'Условие удалено'];
        }
    }

    public function find($id)
    {
        return Condition::find($id);
    }

    public function save(ConditionRequest $request)
    {
        if ($request->has('edit')) {
            $data = $request->except('_token', 'id', 'edit');
            $condition = Condition::find($request->id);

            if ($condition->update($data)) {
                return ['status' => 'Условие обновлено'];
            } else {
                return ['error' => 'Ошибка при обновлении'];
            }
        } else {
            $data = $request->except('_token', 'id', 'edit');
            if (Condition::create($data)) {
                return ['status' => 'Условие добавлено'];
            } else {
                return ['error' => 'Ошибка при добавлении'];
            }
        }
    }

    /*                      API                      */

    public function getTermsAndConditions()
    {
        $conditions = Condition::all();

        return response()->json([
            'status' => 1,
            'conditions' => $conditions
        ]);
    }
}
