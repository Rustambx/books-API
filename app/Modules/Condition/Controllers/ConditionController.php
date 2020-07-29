<?php

namespace App\Modules\Condition\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Condition\Requests\ConditionRequest;
use Condition;

class ConditionController extends Controller
{
    public function addForm()
    {
        if (!auth()->user()->can('ADD_CONDITION')) {
            return view('403');
        }

        $this->title('Add Condition');

        $this->view('condition::add');

        return $this->render();
    }

    public function deleteCondition($id)
    {
        if (!auth()->user()->can('DELETE_CONDITION')) {
            return view('403');
        }

        $result = Condition::delete($id);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('condition')->with($result);
    }

    public function editCondition($id)
    {
        if (!auth()->user()->can('EDIT_CONDITION')) {
            return view('403');
        }

        $condition = Condition::find($id);
        $this->title('Condition edit');

        $this->view('condition::edit');

        return $this->render(compact('condition'));

    }

    public function save(ConditionRequest $request)
    {
        $result = Condition::save($request);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('condition')->with($result);
    }

    public function showList()
    {
        if (!auth()->user()->can('VIEW_CONDITION_LIST')) {
            return view('403');
        }

        $conditions = Condition::all();
        $this->title('Conditions');

        $this->view('condition::list');

        return $this->render(compact('conditions'));
    }

    /*                      API                      */

    public function getTermsAndConditions()
    {
        return Condition::getTermsAndConditions();
    }
}
