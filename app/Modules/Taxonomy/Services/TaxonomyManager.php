<?php

namespace App\Modules\Taxonomy\Services;

use Alias;
use App\Modules\Taxonomy\Requests\{
    SaveTerm,
    SaveVocabulary
};
use App\Modules\Taxonomy\Models\{
    Term,
    Vocabulary};
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class TaxonomyManager
{
    public function buildTree(Vocabulary $vocabulary)
    {
        $tree = $vocabulary->terms()
            ->defaultOrder()
            ->get()
            ->toTree();

        return $tree;
    }

    public function buildVocabularyTree(Vocabulary $vocabulary)
    {
        $tree = $vocabulary->terms()
            ->withDepth()
            ->defaultOrder()
            ->get();

        return $tree;
    }

    public function deleteTerm($id)
    {
        $term = Term::where('id', $id)->first();
        if ($term->parent_id == 0) {
            $refTerms = Term::all()->where('parent_id', $term->id);
            foreach ($refTerms as $refTerm) {
                $refTerm->delete();
            }
            $term->delete();
            return redirect()->route('taxonomy.terms.list', $term->vocabulary_id);
        } else {
            $term->delete();
            return redirect()->route('taxonomy.terms.list', $term->vocabulary_id);
        }
    }

    public function findVocabulary($id)
    {
        return Vocabulary::find($id);
    }

    public function getCategoriesList($sort, $limit = 0, $locale = 'ru')
    {
        $vocabulary = Vocabulary::where('slug', 'categories_'.$locale)->first();

        $items = $vocabulary->terms()
            ->whereIsRoot()
            ->orderBy($sort['column'], $sort['order']);

        if ($limit) {
            $items->limit($limit);
        }

        $items = $items->get();

        return $items;
    }

    public function rebuildTree(Request $request)
    {
        if ($request->has('data')) {
            $data = json_decode($request->input('data'), true);

            Term::rebuildTree($data);

            return $this->apiResponse([
                'message' => __('Terms tree updated.')
            ]);
        }
    }

    public function resolveTerm(Term $term):Term
    {
        if (strpos($term->vocabulary, 'categories') !== false) {
            $term->title = __('Online courses in :term category', ['term' => $term->name]);
        }

        return $term;
    }

    public function saveTerm(SaveTerm $request)
    {
        if ($request->has('edit')) {  // Update term
            $term = Term::find($request->input('id'));

            $term->update([
                'vocabulary_id' => $request->vid,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'icon' => $request->icon,
            ]);

            if ($request->parent && $request->parent != $term->parent_id) {
                $parentTerm = Term::find($request->parent);

                if ($parentTerm) {
                    $term->appendToNode($parentTerm)
                         ->save();
                }
            }
        } else { // Create term
            $term = Term::create([
                'vocabulary_id' => $request->vid,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'icon' => $request->icon,
            ]);


            if ($request->parent) {
                $parentTerm = Term::find($request->parent);

                if ($parentTerm) {
                    $term->appendToNode($parentTerm)
                         ->save();
                }
            }
        }

        // Generate alias

        return redirect()->route('taxonomy.terms.list', $request->vid);
    }

    public function saveVocabulary(SaveVocabulary $request)
    {
        if ($request->has('edit')) {
            $vocabulary = Vocabulary::find($request->id);

            $vocabulary->update([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->description,
            ]);

            return ['status' => 'Словарь обновлен'];
        } else {
            Vocabulary::create([
                     'name' => $request->input('name'),
                     'slug' => $request->input('slug'),
                     'description' => $request->description,
                 ]);

            return ['status' => 'Словарь добавлен'];
        }

    }

    public function term()
    {
        return new Term;
    }

    public function vocabulary()
    {
        return new Vocabulary;
    }

    public function apiFilterVocabularies(Request $request):array
    {
        $page = $request->page ?: 1;
        $sorting = $request->sorting;

        // Start building the query
        $query = Vocabulary::whereNotNull('id');

        // Count total rows
        $total = $query->count('id');

        // Filter by search
        if ($request->search) {
            $query->where('name', 'like',"%{$request->search}%");

            $total = $query->count('id');
        }

        // Finish and run the query
        $result = $query->offset(($page - 1) * $request->limit)
            ->limit($request->limit)
            ->sort($sorting)
            ->sort(['column' => 'weight', 'order' => 'asc'])
            ->get();

        // Parse results
        $data = collect();

        if ($result->isNotEmpty()) {
            foreach ($result as $row) {
                $item = [];

                $item['id'] = $row->id;
                $item['name'] = $row->name.'&nbsp;<small class="text-muted">('.$row->slug.')</small>';
                $item['locale'] = $row->locale;
                $item['actions'] = '
                <a href="'.route('taxonomy.terms.list', $row->id).'" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-list"></i>
                </a>
                <a href="'.route('taxonomy.vocabulary.edit', $row->id).'" class="btn btn-sm btn-outline-primary">
                    <i class="fa fa-edit"></i>
                </a>
                <button type="button" data-name="'.$row->name.'" data-id="'.$row->id.'" data-toggle="modal" data-target="#delete-modal" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-trash-alt"></i>
                </button>';

                $data->push($item);
            }
        }

        return [
            'rows' => $data,
            'total' => $total,
            'totalFiltered' => $result->count()
        ];
    }

    public function apiGetCategories(Request $request)
    {
        $vocabulary = Vocabulary::where('slug', 'categories_'.$request->input('locale'))->first();
        $items = collect();

        if ($request->has('rootOnly')) {
            $query = $vocabulary->terms()
                ->whereIsRoot()
                ->defaultOrder()
                ->get();

            return $this->apiResponse([
                'items' => $query,
                'status' => 1
            ]);
        }

        $query = $vocabulary->terms()
            ->defaultOrder()
            ->get()
            ->toTree();

        if ($query->isNotEmpty()) {
            $traverse = function ($categories) use (&$traverse) {
                foreach ($categories as $category) {
                    $category->alias = Alias::getAlias('term/'.$category->id);

                    $traverse($category->children);
                }
            };

            $traverse($query);
        }

        return $this->apiResponse([
            'items' => $query,
            'status' => $items->isNotEmpty()
        ]);
    }

    public function apiGetLanguages(Request $request)
    {
        $vocabulary = Vocabulary::where('slug', 'languages_'.$request->input('locale'))->first();

        if (!$vocabulary) {
            return $this->apiResponse([
                'status' => 0,
            ], 422);
        }

        $query = $vocabulary->terms()->get();

        return $this->apiResponse([
            'items' => $query,
            'status' => $query->isNotEmpty()
        ]);
    }

    public function apiGetSubCategories(Request $request)
    {
        $vocabulary = Vocabulary::where('slug', 'categories_'.$request->input('locale'))->first();

        $query = $vocabulary->terms()
//            ->where('parent_id', $request->parentId)
            ->defaultOrder()
            ->withDepth()
            ->descendantsOf($request->parentId);

        return $this->apiResponse([
            'items' => $query,
            'status' => $query->isNotEmpty()
        ]);
    }

    public function apiGetTermData($termId)
    {
        $term = $this->term()->find($termId);

        $term->load('descendants');

        return $this->apiResponse([
            'term' => $term
        ]);
    }

    public function apiGetTopCategories()
    {
        $locale = \App::getLocale();
        $vocabulary = Vocabulary::where('slug', 'categories_'.$locale)->first();

        $result = $this->term()
            ->select('terms.*')
            ->selectRaw('COUNT(cp.id) as cnt')
            ->join('courses as c', 'c.category_id', '=', 'terms.id')
            ->join('course_purchases as cp', 'cp.course_id', '=', 'c.id')
            ->where('terms.vocabulary_id', $vocabulary->id)
            ->groupBy('terms.id', 'terms.vocabulary_id')
            ->orderBy('cnt', 'desc')
            ->get();

        if ($result->isNotEmpty()) {
            $traverse = function ($categories) use (&$traverse) {
                foreach ($categories as $category) {
                    if (count($category->ancestors)) {
                        $category->id = $category->ancestors[0]->id;
                        $category->name = $category->ancestors[0]->name;
                        $category->alias = Alias::getAlias('term/'.$category->ancestors[0]->id);
                        $category->icon = $category->ancestors[0]->icon;
                    } else {
                        $category->alias = Alias::getAlias('term/'.$category->id);
                    }
                }
            };

            $traverse($result);
        } else {
            return $this->apiResponse([
                'items' => [],
                'status' => 0
            ]);
        }

        return $this->apiResponse([
            'items' => $result->unique('id'),
            'status' => 1
        ]);
    }

    public function deleteVocabulary($id)
    {
        $vocabulary = Vocabulary::find($id);
        if ($vocabulary->delete()) {
            return ['status' => 'Словарь удален'];
        }
    }
}
