<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Classes\Main;
use DB;
use Validator;
use Hash;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::user();
        $this->main = new Main();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = $this->user;
        return view('main.user.dashboard', compact('user'));
    }

    public function admin()
    {
        $admin = DB::table('users')->where('roles', 4)->where('status', 0)->get();
        return view('main.user.admin',compact('admin'));
    }

    public function getDetailAdmin($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        return view('main.user.detail_admin', compact('user'));
    }

    public function delete($id)
    {
        DB::table('users')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect()->back();
    }

    public function addAdmin()
    {
        return view('main.user.add_admin');
    }

    public function postAddAdmin(Request $request)
    {
        $rules = [
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if(!$validator){
            return redirect()->back()->with(['msg' => $validator->errors()->all()]);
        }
        $data = [
            'name' => 'admin',
            'username' => $request->username,
            'kelas' => 0,
            'nisn' => 0,
            'email' => '',
            'password' => hash::make($request->password),
            'otp' => mt_rand(100000, 999999),
            'tempat_lahir' => '',
            'tanggal_lahir' => '',
            'thumbnail' => '',
            'roles' => 4,
        ];
        DB::table('users')->insert($data);
        return redirect()->route('admin')->with(['msg' => 'Berhasil Menambahkan Data']);
    }

    public function siswa()
    {
        $user = DB::table('users')->where('roles', 0)->where('status', 0)->get();
        return view('main.user.siswa', compact('user'));
    }

    public function siswaAdd()
    {
        return view('main.user.add_siswa');
    }

    public function siswaAddPost(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'kelas' => 'required',
            'nisn' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'thumbnail' => 'required',
            'full_name' => 'required',
            'telp' => 'required',
            'gender' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if(!$validator){
            return redirect()->back()->withErrors($validator);
        }
        $file = $request->file('thumbnail');
        $fileName = $file->getClientOriginalName();
        $location = 'images/';
        $data = [
            'name' => $request->nama,
            'fullname' => $request->full_name,
            'username' => str_replace(' ','_',$request->name),
            'gender' => $request->gender,
            'kelas' => $request->kelas,
            'nisn' => $request->nisn,
            'email' => $request->email,
            'password' => hash::make('123456789'),
            'telp' => $request->telp,
            'otp' => mt_rand(100000, 999999),
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'thumbnail' => $location.$fileName,
            'roles' => 0,
            'created_at' => Carbon::now()
        ];
        try{

            $createAccount = $this->main->createMandatory($data);
            $user = DB::table('users')->insert($data);
            $file->move($location, $fileName);

            return redirect()->route('siswa')->with(['msg' => 'Berhasil Menambahkan Data']);
        } catch(\Exception $e)
        {
            dd($e);
        }
    }

    public function getDetailSiswa($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        return view('main.user.add_siswa', compact('user'));
    }

    public function siswaDelete($id)
    {
        DB::table('users')->where('id', $id)->update([
            'status' => 1
        ]);
        return redirect()->back();
    }

    public function tagihan()
    {
        $tagihan = DB::table('tagihans')->get();
        return view('main.user.tagihan', compact('tagihan'));
    }

    public function tagihanAdd()
    {
        $tipe = DB::table('tipe_tagihan')->where('status', 1)->get();
        return view('main.user.add_tagihan', compact('tipe'));
    }

    public function tagihanAddPost(Request $request)
    {
        $rules = [
            'tipe_tagihan' => ['required', 'exists:tipe_tagihan,id'],
            'tipe' => 'required',
            'jumlah' => 'required',
            'nisn' => ['required',
                        // 'exists:users,nisn'
                    ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if(!$validator){
            return redirect()->back()->withErrors($validator);
        }

        $data = [
            'tipe_tagihan' => $request->tipe_tagihan,
            'tipe' => $request->tipe,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'nisn' => $request->nisn,
            'created_at' => Carbon::now()
        ];
        DB::table('tagihans')->insert($data);
        return redirect()->route('tagihan')->with(['msg' => 'Berhasil Menambahkan Data']);
    }

    public function Saldos()
    {
        $user = DB::table('users')->where('roles', 0)->where('status', 0)->get();
        return view('main.user.topup', compact('user'));
    }

    public function SaldosPost(Request $request)
    {
        $rules = [
            'user_id' => ['required',
                        // 'exists:users,nisn'
                    ],
            'nominal' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);
        if(!$validator){
            return redirect()->back()->withErrors($validator);
        }
        $topup = $this->main->TopUp($request->all(), 1);
        return redirect()->route('tagihan')->with(['msg' => 'Berhasil Menambahkan Data']);
    }

    public function history()
    {
        $data = DB::select("select u.*, keterangan, tt.tipe_tagihan as tagihan,t.jumlah,t.tipe, tu.order_number from tagihan_user tu
        left join users u on u.id = tu.user_id
        left join tagihans t on tu.tagihan_id = t.id
        left join tipe_tagihan tt on t.tipe_tagihan = tt.id ");

        return view('main.user.history', compact('data') );
    }

    public function listSaldo()
    {
        $data = DB::table('users')->where('roles', 0)->where('status', 0)->get();
        return view('main.user.saldo', compact('data'));
    }

    public function api()
    {
        $main = $this->main->updateSaldo(9,1000);
        return $main;
    }

    public function waiting()
    {
        $tagihan = $this->main->riwayatAdmin();
        return view('main.user.waiting_list', compact('tagihan'));
    }

    public function accept($id)
    {
        $user = Auth::user();
        $tagihan = DB::table('transactions')->where('id', $id)->first();
        $data = [
            'nisn' => $tagihan->nisn,
            'nominal' => $tagihan->jumlah_bayar,
        ];
        $topup = $this->main->TopUp($data, 1);
        $this->main->updateStatusTransaction($id);
        return redirect()->back();
    }
    public function deny($id)
    {
        $cancel = DB::table('transactions')->where('id', $id)->update([
            'status' => 'deny'
        ]);
    }
}