<?php

namespace App\Modules\Taxonomy\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Taxonomy\Models\Vocabulary;
use App\Modules\Taxonomy\Requests\SaveVocabulary;
use Taxonomy;

class VocabularyController extends Controller
{
    // TODO: Удаление словаря вместе с терминами

    public function addVocabulary()
    {
        if (!auth()->user()->can('ADD_VOCABULARY')) {
            return view('403');
        }

        $this->title(__('Add vocabulary'));

        $this->view('taxonomy::vocabulary.add');

        return $this->render();
    }

    public function deleteVocabulary($id)
    {
        if (!auth()->user()->can('DELETE_VOCABULARY')) {
            return view('403');
        }

        $result = Taxonomy::deleteVocabulary($id);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('taxonomy')->with($result);
    }

    public function editVocabulary(\App\Modules\Taxonomy\Models\Vocabulary $vocabulary)
    {
        if (!auth()->user()->can('EDIT_VOCABULARY')) {
            return view('403');
        }

        $this->title(__('Edit vocabulary'));

        $this->view('taxonomy::vocabulary.edit');

        return $this->render(compact('vocabulary'));
    }

    public function saveVocabulary(SaveVocabulary $request)
    {
        $result = Taxonomy::saveVocabulary($request);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return redirect()->route('taxonomy')->with($result);

    }

    public function showVocabularies()
    {
        if (!auth()->user()->can('VIEW_VOCABULARY_LIST')) {
            return view('403');
        }

        $vocabularies = Vocabulary::all();
        $this->title(__('Vocabularies'));

        $this->view('taxonomy::vocabulary.list');

        return $this->render(compact('vocabularies'));
    }
}
