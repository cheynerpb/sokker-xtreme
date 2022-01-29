<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SystemUser;

class SystemUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view_data['system_users'] = SystemUser::paginate(10);

        return view('system_users.index', compact('view_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function active_user(Request $request, $id)
    {
        try {
            $user = SystemUser::find($id);

            if($user){
                $user->update(['active' => !$request->active]);

                return redirect()->back()->with(array(
                    'message_id' => 'El usuario fue actualizado',
                    'error' => false
                ));
            }

            return redirect()->back()->with(array(
                'message_id' => 'No existe el usuario',
                'error' => true
            ));
        } catch (\Throwable $th) {
            return redirect()->back()->with(array(
                'message_id' => $th->getMessage(),
                'error' => true
            ));
        }
    }
}
