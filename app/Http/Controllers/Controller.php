<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function render(array $data = [])
    {
        $data['title'] = $this->title;

        return view($this->view)
            ->with($data);
    }

    protected function title(string $title): void
    {
        $this->title = $title;
    }

    protected function view(string $view): void
    {
        $this->view = $view;
    }
}
