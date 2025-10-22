<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = Label::paginate();
        return view('labels.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $label = new Label();
        return view('labels.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:labels',
            'description' => "max:1000"
        ]);

        $label = new Label();
        $label->fill($data);
        $label->save();
        flash('Метка успешно создана')->success();

        return redirect()->route('labels.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label)
    {
        return view('labels.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Label $label)
    {
        $data = $request->validate([
            'name' => "required|unique:labels,name,{$label->id}",
            'description' => "max:1000"
        ]);
        $label->fill($data);
        $label->save();

        flash('Метка успешно изменена')->success();
        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        try {
            $label->delete();
            flash('Метка успешно удалена')->success();
        } catch(QueryException $qe) {
            flash('Не удалось удалить метку')->error();
        }

        return redirect()->route('labels.index');
    }
}
