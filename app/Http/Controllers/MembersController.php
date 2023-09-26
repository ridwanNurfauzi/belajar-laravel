<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class MembersController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $members = Role::where('name', 'member')->first()->users;
            return DataTables::of($members)
                ->addColumn('name', function ($member) {
                    return view('datatable._adminMemberName', [
                        'url' => route('members.show', $member->id),
                        'name' => $member->name
                    ]);
                })
                ->addColumn('action', function ($member) {
                    return view('datatable._action', [
                        'model' => $member,
                        'form_url' => route('members.destroy', $member->id),
                        'edit_url' => route('members.edit', $member->id),
                        'confirm_message' => 'Yakin mau menghapus ' . $member->name . '?'
                    ]);
                })->make(true);
        }
        $html = $htmlBuilder
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama'])
            ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false]);
        return view('members.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request)
    {
        $password = str_random(6);
        $data = $request->all();
        $data['password'] = bcrypt($password);
        $data['is_verified'] = 1;
        $member = User::create($data);
        $memberRole = Role::where('name', 'member')->first();
        $member->addRole($memberRole);
        Mail::send('auth.emails.invite', compact('member', 'password'), function ($m) use ($member) {
            $m->to($member->email, $member->name)->subject('Anda telah didaftarkan di Larapus!');
        });
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan member dengan email " .
                "<strong>" . $data['email'] . "</strong>" .
                " dan password <strong>" . $password . "</strong>."
        ]);
        return redirect()->route('members.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = User::find($id);
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = User::find($id);
        return view('members.edit')->with(compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, string $id)
    {
        $member = User::find($id);
        $member->update($request->only('name', 'email'));
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan $member->name"
        ]);
        return redirect()->route('members.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = User::find($id);
        if ($member->hasRole('member')) {
            $member->delete();
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Member berhasil dihapus"
            ]);
        }
        return redirect()->route('members.index');
    }
}
