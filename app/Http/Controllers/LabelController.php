<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use App\Http\Requests\LabelRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LabelController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $labels = Label::paginate();
        return view('labels.index', compact('labels'));
    }

    public function create()
    {
        $this->authorize('create', Label::class);
        return view('labels.create');
    }

    public function store(LabelRequest $request)
    {
        $this->authorize('create', Label::class);

        $data = $request->validated();

        $label = new Label();
        $label->fill($data);
        $label->save();
        flash(__('controllers.label_create'))->success();

        return redirect()->route('labels.index');
    }

    public function edit(Label $label)
    {
        $this->authorize('update', $label);

        return view('labels.edit', compact('label'));
    }

    public function update(Request $request, Label $label)
    {
        $this->authorize('update', $label);

        $data = $request->validate([
            'name' => "required|unique:labels,name,{$label->id}",
            'description' => "max:1000"
        ]);
        $label->fill($data);
        $label->save();

        flash(__('controllers.label_update'))->success();
        return redirect()->route('labels.index');
    }

    public function destroy(Label $label)
    {
        $this->authorize('delete', $label);

        if ($label->tasks()->exists()) {
            flash(__('controllers.label_statuses_destroy_failed'))->error();
            back();
        }
        $label->delete();

        flash(__('controllers.label_destroy'))->success();
        return redirect()->route('labels.index');
    }
}
