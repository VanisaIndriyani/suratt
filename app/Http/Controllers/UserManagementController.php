<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    private const ROLES = ['staf', 'asmin', 'asops', 'kasatker', 'kaskogartap'];

    private const JABATANS = [
        'ASOPS',
        'ASMIN',
        'DANDENPOM',
        'KASINTEL',
        'KASIOPS',
        'KASIPERS',
        'KASILOG',
        'KAPROT',
        'KAMAK',
        'KASSUBKOGARTAP 0501/JP',
        'KASSUBKOGARTAP 0502/JU',
        'KASSUBKOGARTAP 0503/JB',
        'KASSUBKOGARTAP 0504/JS',
        'KASSUBKOGARTAP 0505/JT',
        'KASSUBKOGARTAP 0506/TGR',
        'KASSUBKOGARTAP 0507/BKS',
        'KASSUBKOGARTAP 0508/DPK',
        'KASSUBKOGARTAP 0509/KAB. BKS',
        'KASSUBKOGARTAP 0510/TRS',
        'DANSATINTEL',
        'DANDENMA',
        'KASET',
        'PASIRENGAR',
        'PAKES',
        'PAPEN',
        'KEPRIMKOP',
        'SPRI',
        'STAF',
        'KASKOGARTAP',
    ];

    private function roleFromJabatan(?string $jabatan): string
    {
        $value = strtolower(trim((string) $jabatan));

        if ($value === '') {
            return 'staf';
        }

        if ($value === 'kaskogartap') {
            return 'kaskogartap';
        }

        if ($value === 'asmin') {
            return 'asmin';
        }

        if ($value === 'asops') {
            return 'asops';
        }

        if ($value === 'staf') {
            return 'staf';
        }

        return 'kasatker';
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $items = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('no_hp', 'like', "%{$q}%")
                        ->orWhere('jabatan', 'like', "%{$q}%")
                        ->orWhere('role', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', [
            'items' => $items,
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'jabatanOptions' => self::JABATANS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255', 'in:'.implode(',', self::JABATANS)],
            'role' => ['nullable', 'string', 'max:255', 'in:'.implode(',', self::ROLES)],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $role = $validated['role'] ?? $this->roleFromJabatan($validated['jabatan']);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'jabatan' => $validated['jabatan'],
            'role' => $role,
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'item' => $user,
            'jabatanOptions' => self::JABATANS,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'no_hp' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255', 'in:'.implode(',', self::JABATANS)],
            'role' => ['nullable', 'string', 'max:255', 'in:admin,'.implode(',', self::ROLES)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $computedRole = $validated['role'] ?? $this->roleFromJabatan($validated['jabatan']);
        $role = $user->role === 'admin' ? 'admin' : $computedRole;

        $user->fill([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'jabatan' => $validated['jabatan'],
            'role' => $role,
        ]);

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
