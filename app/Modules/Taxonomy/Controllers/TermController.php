<?php

namespace App\Modules\Taxonomy\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Taxonomy\Requests\SaveTerm;
use Taxonomy;

class TermController extends Controller
{
    public function addTerm($vocabularyId)
    {
        if (!auth()->user()->can('ADD_TERM')) {
            return view('403');
        }

        $this->title(__('Add term'));

        $this->view('taxonomy::terms.add');

        $vocabulary = Taxonomy::findVocabulary($vocabularyId);
        $tree = Taxonomy::buildVocabularyTree($vocabulary);

        return $this->render(compact('tree', 'vocabulary'));
    }

    public function deleteTerm($id)
    {
        if (!auth()->user()->can('DELETE_TERM')) {
            return view('403');
        }

        return Taxonomy::deleteTerm($id);
    }

    public function displayTerm($termId)
    {
        $term = Taxonomy::term()->findOrFail($termId);
        $term = Taxonomy::resolveTerm($term);

        $this->view('category.index');

        $this->title($term->title);

        return $this->render(compact('term'));
    }

    public function editTerm(\App\Modules\Taxonomy\Models\Term $term)
    {
        if (!auth()->user()->can('EDIT_TERM')) {
            return view('403');
        }

        $this->title(__('Edit term'));

        $this->view('taxonomy::terms.edit');

        $tree = Taxonomy::buildVocabularyTree($term->vocabulary);
        $vocabulary = $term->vocabulary;

        return $this->render(compact('term', 'tree', 'vocabulary'));
    }

    public function saveTerm(SaveTerm $request)
    {
        return Taxonomy::saveTerm($request);
    }

    public function showTerms($vocabularyId)
    {
        if (!auth()->user()->can('VIEW_TERM_LIST')) {
            return view('403');
        }

        $vocabulary = Taxonomy::findVocabulary($vocabularyId);

        $this->title(__('Terms in :vocabulary vocabulary', ['vocabulary' => $vocabulary->name]));

        $this->view('taxonomy::terms.list');

        $tree = Taxonomy::buildVocabularyTree($vocabulary);

        return $this->render(compact('tree', 'vocabulary'));
    }

}
