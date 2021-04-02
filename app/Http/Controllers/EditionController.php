<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ContestEdition;

class EditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view_data['editions'] = ContestEdition::orderBy('created_at', 'desc')->get();

        return view('editions.index', compact('view_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('editions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        if ($request->active) {
            ContestEdition::where('active', true)->update(['active' => false]);
        }

        ContestEdition::create([
            'name' => $request->name,
            'active' => $request->active ? true : false,
        ]);



        return redirect()->route('editions.index')
            ->with('success', 'Edición insertada correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $view_data['edition'] = ContestEdition::find($id);

        if ($view_data['edition']) {
            return view('editions.edit', compact('view_data'));
        }
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $edition = ContestEdition::find($id);

        $edition->update([
            'name' => $request->name,
            'active' => $request->active ? true : false,
        ]);

        return redirect()->route('editions.index')
            ->with('success', 'Edición modificada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $edition = ContestEdition::find($id);
        if ($edition) {
            $edition->delete();

            return redirect()->route('editions.index')
                ->with('success', 'Edición eliminada correctamente');
        }
        return redirect()->back();
    }
}
