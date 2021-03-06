<?php

namespace App\Http\Controllers;

use App\Notifications\ProfileChange;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use User;
use Clan;
use Role;
use Family;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: pcshooter
 * Date: 04.10.14
 * Time: 14:44
 */

class UserController extends Controller
{

    /**
     * @param $id
     * @return mixed
     */
    public function showProfile($id)
    {
        $user = User::find($id);
        if (!is_object($user)) {
            return back()->withErrors('Benutzer nicht gefunden');
        }
        $disabledForm = 'disabled';
        $requIred = ['', ''];
        if (User::isManager() || User::isLoggedAdmin() || $id == auth()->id()) {
            $disabledForm = '';
            $requIred = ['requ', 'required'];
        }

        $cs = DB::select(DB::raw("select country_name_" . trans('formats.langjs') . " as country_name, country_code from countries order by country_name_" . trans('formats.langjs') . " asc"));
        $countries = [];
        foreach ($cs as $c) {
            $countries[$c->country_code] = $c->country_name;
        }
        asort($countries);
        $clan = DB::table('clans')->where('id', '=', $user->clan_id)->select('id', 'clan_description', 'clan_code')->first();
        $families = DB::table('families')->where('id', '=', $user->family_code)->select('family_description')->first();
        if (!is_null($families)) {
            $user->family_description = $families->family_description;
        } else {
            $user->family_description = '';
        }
        $birthDayMsg = '';
        if (isset($user->user_birthday)) {
            $d = \DateTime::createFromFormat('Y-m-d H:i:s', $user->user_birthday);
            if ($d && $d->format('Y-m-d H:i:s') === $user->user_birthday) {
                $user->user_birthday = \DateTime::createFromFormat('Y-m-d H:i:s', $user->user_birthday)->format('d.m.Y');
            } else {
                $user->user_birthday = null;
                $birthDayMsg = 'Möchtest Du Dein Geburtsdatum angeben?';
            }
        }
        return view('user.profile')
            ->with('info_message', $birthDayMsg)
            ->with('user', $user)
            ->with('countries', $countries)
            ->with('disabledForm', $disabledForm)
            ->with('requIred', $requIred)
            ->with('clan_desc', $clan->clan_description);
    }

    /**
     * Saves the auth. users profile
     *
     * @param $id
     * @return mixed Redirect
     */
    public function saveProfile($id)
    {
        $file = null;
        $new_mail_message = '';
        Input::flash();
        $user = User::find($id);
        if (!is_object($user)) {
            return back()->withErrors('Benutzer nicht gefunden');
        }
        $user_old_email = $user->email;
        $rules = [
            'user_first_name' => 'required|min:3|regex:/^[a-zA-ZòùèàéèôâêöäüÜÖÄ\.\-\ ]+$/',
            'user_name' => 'required|min:3|regex:/^[a-zA-ZòùèéàèôâêöäüÜÖÄ\.\-\ ]+$/',
            'user_login_name' => 'required|regex:/^[a-zA-Z\.]+$/',
            'email' => 'required|email',
            'user_www_label' => 'max:50',
            'user_address' => 'required|min:3',
            'user_city' => 'required|min:3',
            'user_zip' => 'required|min:3',
            'user_country_code' => 'required',
            'user_fon1_label' => 'required',
            'user_fon1' => 'required|min:3',
        ];
        if (Input::hasFile('user_avatar')) {
            $file = Input::file('user_avatar');
            $rules = [
                'user_first_name' => 'required|min:3|regex:/^[a-zA-ZòùèàéèôâêöäüÜÖÄ\.\-\ ]+$/',
                'user_name' => 'required|min:3|regex:/^[a-zA-ZòùèéàèôâêöäüÜÖÄ\.\-\ ]+$/',
                'user_login_name' => 'required|regex:/^[a-zA-Z\.]+$/',
                'email' => 'required|email',
                'user_www_label' => 'max:50',
                'user_address' => 'required|min:3',
                'user_city' => 'required|min:3',
                'user_zip' => 'required|min:3',
                'user_country_code' => 'required',
                'user_fon1_label' => 'required',
                'user_fon1' => 'required|min:3',
                'user_avatar' => 'image',
            ];
        }
        if ($id != Auth::user()->id && (!User::isManager() || !User::isLoggedAdmin())) {
            Session::put('error', '<div class="messagebox">Dies ist nicht Dein Profil</div>');
            return Redirect::back()->withErrors('<div class="messagebox">Dies ist nicht Dein Profil</div>');
        }
        $validator = Validator::make(
            Input::all(),
            $rules,
            [
                'regex' => '<div class="messagebox">Format des Felds <span class="error-field"><span class="error-field">:attribute</span></span> ist ungültig:<br>
                            - Keine Umlaute<br>
                            - Keine Sonderzeichen<br>
                            Gültiges Format:
                            <b>vorname.name</b></div>',
                'not_in' => '<div class="messagebox">Das Feld <span class="error-field"><span class="error-field">:attribute</span></span> darf nicht leer sein</div>',
                'boolean' => '<div class="messagebox">Dies ist nicht Dein Profil</div>'
            ]
        );
        if ($validator->fails()) {
            return back()->withErrors($validator->messages());
        }
        if ($file != null) {
            $path = public_path() . '/files/' . str_replace('.', '_', $user->user_login_name);
            $fileName = str_replace('.', '_', $user->user_login_name) . '_avatar.' . $file->getClientOriginalExtension();
            $savePath = str_replace(public_path(), '', $path);
            $file->move($path, $fileName);
            $image = Image::make($path . '/' . $fileName);
            $image->resize(125, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->save();
            $user->user_avatar = $savePath . '/' . $fileName;
        }
        $fon1label = (strlen(Input::get('user_fon1_label')) <= 2) ? 'x' : ltrim(Input::get('user_fon1_label'), '#');
        $args = Input::except('user_fon1_label_show', 'user_fon2_label_show', 'user_fon3_label_show', 'user_avatar', 'user_birthday');
        $birthday = \DateTime::createFromFormat('d.m.Y', Input::get('user_birthday'));
        if (is_object($birthday)) {
            $user->user_birthday = $birthday->format('Y-m-d') . ' 00:00:00';
        }
        $user->user_new = 0;
        $user->update($args);
        $user->user_fon1_label  = $fon1label;


        $user->push();
        // Receiver of the email for making changes in the mailing list
        $recipientUsers = User::whereHas(
            'roles', function ($q) {
                $q->where('role_code', 'ADMIN');
        }
        )->get();
        $data = ['id' => $user->id, 'old_email' => $user_old_email, 'email' => $user->email, 'login' => $user->user_login_name];
        if (!str_is($user_old_email, $user->email)) {
            $new_mail_message = '<br>' . trans('errors.new_mail');
            \Notification::send($recipientUsers, (new ProfileChange($user, $data)));
        }
        $tu = (User::isManager() || User::isLoggedAdmin()) ? 'Das' : 'Dein';
        return Redirect::back()
            ->with('user', $user)
            ->with('info_message', trans('errors.data-saved', ['a' => $tu, 'data' => 'Profil']) . $new_mail_message);
    }

    /**
     * Gets a user by id
     *
     * @param $id User->id
     * @return mixed View
     */
    public function showEditUser($id)
    {
        if (!isset($id) || !is_numeric($id)) {
            return Redirect::back();
        }
        $u = User::find($id);
        $clan = $u->getUserClanName($u->clan_id);
        $u->clan = $clan[0]->clan_description;
        $families = Family::select('id', 'clan_id', 'family_description')->get();
        $allFams = [];
        foreach ($families as $key => $f) {
            $allFams[$f->clan_id][$f->id] = $f->family_description;
        }
        $cs = DB::select(DB::raw("select country_name_" . trans('formats.langjs') . " as country_name, id, country_code from countries order by country_name_" . trans('formats.langjs') . " asc"));
        foreach ($cs as $c) {
            $countries[$c->id] = $c->country_name;
        }
        asort($countries);
        $transRoles = [];
        $t = [];
        if (!$u->roles->isEmpty()) {
            foreach ($u->roles as $r) {
                $t[] = $r->role_code;
            }
        } else {
            $t[] = '';
        }
        $roles = DB::table('roles')
                ->select('role_code', 'id')
                ->whereNotIn('role_code', $t)
                ->whereNotIn('role_code', ['ADMIN'])
                ->where('role_guest', '=', 0)
                ->get();
        foreach ($roles as $role) {
            $transRoles[$role->id] = trans('roles.' . $role->role_code);
        }
        $clans = [
            Clan::pluck('clan_description', 'id')->toArray()
        ];
        array_unshift($clans[0] , trans('dialog.select'));
        return view('logged.admin.useredit')
            ->with('clans', $clans[0])
            ->with('user', $u)
            ->with('saved', false)
            ->with('families', $allFams)
            ->with('countries', $countries)
            ->with('allRoles', $transRoles);
    }

    /**
     * Attaches a role to a user
     *
     * @return mixed Redirect
     */
    public function addRoleUser()
    {
        $user = User::find(\request()->input('user_id'));
        $r = DB::table('role_user')
            ->where('user_id', '=', \request()->input('user_id'))
            ->where('role_id', '=', \request()->input('role_id'))
            ->count();
        if ($r > 0) {
            return json_encode('false');
        }
        $user->roles()->attach(\request()->input('role_id'));
        $newRole = Role::find(\request()->input('role_id'));
        $defRoles = [];
        $defRoles[0]['id'] = $newRole->id;
        $defRoles[0]['role_c'] = $newRole->role_code;
        $defRoles[0]['role_code'] = trans('roles.' . $newRole->role_code);
        $defRoles[0]['role_tax_annual'] = $newRole->role_tax_annual;
        $defRoles[0]['role_tax_night'] = $newRole->role_tax_night;
        $defRoles[0]['role_tax_stock'] = $newRole->role_tax_stock;
        $defRoles[0]['role_guest'] = $newRole->role_guest;
        foreach ($newRole->rights as $r) {
            $defRoles[0]['role_rights'][] = trans('rights.' . $r->right_code);
        }

        return json_encode($defRoles);
    }

    /**
     * Detaches a role to a user
     *
     * @return mixed Redirect
     */
    public function deleteRoleUser()
    {
        $roleId = request()->input('role_id');
        $user = User::find(request()->input('user_id'));
        $role = Role::find(request()->input('role_id'));
        if (!is_object($user) || !is_object($role)) {
            return json_encode([]);
        }
        $validator = Validator::make(
            ['role_id' => $role->role_code . '|size:2'],
            ['size' => 'trans(\'errors.del-admin\')']
        );
        if ($validator->fails()) {
            Session::put('error', $validator);
            return Redirect::intended()->withErrors($validator);
        }
        if (is_object($user->roles())) {
            $user->roles()->detach($roleId);
        } elseif (is_object($role)) {
            $role->destroy();
        }
        $user->push();
        return json_encode([]);
    }

    public function activateUser ()
    {
        $uID = request()->input('user_id');
        $active = request()->input('user_active');
        $user = User::find($uID);
        if (is_object($user)) {
            $user->user_active = $active;
            $user->push();
        }
        return json_encode([]);
    }
    /**
     * Shows all users
     *
     * @return mixed
     */
    public function showUsers()
    {
        $users = $this->searchUsers();
        $clans = [
            Clan::pluck('clan_description', 'id')->toArray()
        ];
        $roles = [
            Role::where('role_guest', '=', '0')->pluck('role_description', 'id')->toArray()
        ];
        $families = [
            Family::select('family_description', DB::raw('CONCAT(id, "|", clan_id) AS fam_code'))
                ->pluck('family_description', 'fam_code')
                ->toArray()
        ];
        return view('logged.userlist')
            ->with('allUsers', $users)
            ->with('clans', [0 => trans('dialog.all')] + $clans[0])
            ->with('roleList', [0 => trans('dialog.all')] + $roles[0])
            ->with('families', [0 => trans('dialog.all')] + $families[0]);
    }

    /**
     * @param Request $request
     * @return false|string
     * @throws \Throwable
     */
    public function userListPrint (Request $request)
    {
        $inputName = $request->input('print_name_pdf');
        $validator = Validator::make($request->all(), [
            'print_name_pdf' => 'required|min:3'
        ]);
        if ($validator->fails()) {
            $inputName = 'Benutzerliste';
        }
        $sortby = ($request->input('sort_field') == null || strlen($request->input('sort_field')) == 0) ? 'user_name' : $request->input('sort_field');
        $orderby = ($request->input('order_by') == null || strlen($request->input('order_by')) == 0) ? 'ASC' : $request->input('order_by');
        $credentials = [
            'search_user' => $request->input('search_user'),
            'search_clan' => $request->input('clan_search'),
            'search_family' => $request->input('family_search'),
            'search_role' => $request->input('role_search'),
            'sort_field' => $sortby,
            'order_by' => $orderby
        ];

        $pdfTmpName = str_replace(' ', '-', $inputName);
        $pdfName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $pdfTmpName);
        $pdfFinalName = preg_replace('/[^A-Za-z0-9\-]/', '', $pdfName);
        $roles = [
            Role::where('role_guest', '=', '0')->pluck('role_description', 'id')->toArray()
        ];
        $users = $this->searchUsers(true, $credentials);
        $cs = DB::select(DB::raw("select country_name_" . trans('formats.langjs') . " as country_name, country_code from countries order by country_name_" . trans('formats.langjs') . " asc"));
        $countries = [];
        foreach ($cs as $c) {
            $countries[$c->country_code] = $c->country_name;
        }
        asort($countries);
        $data = [
            'allUsers' => $users,
            'countries' => $countries,
            'roleList' => $roles[0],
            'pdfTitle' => $pdfFinalName . '.pdf'

        ];
        $view = view('pdfs.userlist-print', $data);
        $contents = $view->render();
        $mPdfConfig = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 11,
            'default_font' => 'Arial',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 4,
            'orientation' => 'L',
        ];
        try {
            $pdf = new Mpdf($mPdfConfig);
            $pdf->debug = true;
            $pdf->setHTMLHeader('<p style="width: 100%; border-bottom: 1px solid #333333; text-align: center">' . $pdfFinalName . '</p>');
            $pdf->setHTMLFooter('<p style="width: 100%; border-top: 1px solid #333333; text-align: center">{PAGENO} / {nbpg}</p>');
            $pdf->AddPage('L','BLANK','0','1','off');
            $pdf->writeHTML($contents);
            $pdf->Output(public_path() . '/files/___userlists/' . $pdfFinalName . '.pdf', \Mpdf\Output\Destination::FILE);
        } catch (MpdfException $e) {
            $e->getMessage();
        }
        return json_encode(['success' => url('/files/___userlists/' . $pdfFinalName . '.pdf'), 'pdf_name' => $pdfFinalName . '.pdf']);

    }

    /**
     * Searches a user by input
     * @param bool $json
     * @param null $credentials
     * @return false|string
     */
    public function searchUsers($json = false, $credentials = null)
    {
        $user = new User();
        if (is_null($credentials)) {
            Input::flash();
            $sortby = (Input::get('sort_field') == null || strlen(Input::get('sort_field')) == 0) ? 'user_name' : Input::get('sort_field');
            $orderby = (Input::get('order_by') == null || strlen(Input::get('order_by')) == 0) ? 'ASC' : Input::get('order_by');
            $credentials = [
                'search_user' => Input::get('search_field'),
                'search_clan' => Input::get('clan'),
                'search_family' => Input::get('family'),
                'search_role' => Input::get('role'),
                'sort_field' => $sortby,
                'order_by' => $orderby
            ];
        }
        $users = $user->searchUser($credentials);

        if (!$json) {
            return json_encode($users);
        }
        return $users->toArray();
    }
}
