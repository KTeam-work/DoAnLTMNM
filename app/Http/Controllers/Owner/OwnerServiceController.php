<?php


namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class OwnerServiceController extends Controller
{
    public function index(Request $request)
    {
        // Production (flag tắt): guest web bị chuyển về login, không lộ dữ liệu demo.
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        $services = $owner
            ? Service::query()->where('owner_id', $owner->id)->orderBy('name')->get()
            : collect();

        if (! $request->expectsJson()) {
            return view('owner.services.index', compact('services'));
        }

        $this->owner($request);

        return response()->json(['data' => $services]);
    }

    public function create(Request $request)
    {
        $service = new Service([
            'name' => '',
            'description' => '',
            'unit' => 'tháng',
            'status' => 'active',
        ]);

        return view('owner.services.edit', ['service' => $service, 'mode' => 'create']);
    }

    public function edit(Request $request, int $id)
    {
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        $service = $owner
            ? Service::query()->where('owner_id', $owner->id)->find($id)
            : null;

        // Fallback để trang không 500 khi demo/id lạ: dựng object tạm để form vẫn render.
        $service ??= new Service([
            'id' => $id,
            'name' => '',
            'description' => '',
            'unit' => 'tháng',
            'price' => 0,
            'status' => 'active',
        ]);
        $service->id = $id;

        return view('owner.services.edit', ['service' => $service, 'mode' => 'edit']);
    }

    public function store(Request $request)
    {
        $owner = $this->actingOwner($request);
        $data = $this->validatedService($request);
        $data['status'] ??= 'active';
        $service = $owner->services()->create($data);

        if (! $request->expectsJson()) {
            return redirect()->route('owner.services.index')->with('success', 'Thêm dịch vụ thành công.');
        }

        return response()->json(['message' => 'Service created.', 'data' => $service], 201);
    }

    public function update(Request $request, ?Service $service = null)
    {
        $service = $this->ownedService($request, $service);
        $data = $this->validatedService($request, true);
        // Checkbox trạng thái khi bỏ tick sẽ không gửi lên -> hiểu là inactive.
        if (! $request->expectsJson() && ! $request->has('status')) {
            $data['status'] = 'inactive';
        }
        abort_if($data === [], 422, 'Provide at least one service field to update.');
        $service->update($data);

        if (! $request->expectsJson()) {
            return redirect()->route('owner.services.index')->with('success', 'Cập nhật dịch vụ thành công.');
        }

        return response()->json(['message' => 'Service updated.', 'data' => $service->fresh()]);
    }

    public function destroy(Request $request, ?Service $service = null)
    {
        $service = $this->ownedService($request, $service);
        $service->delete();

        if (! $request->expectsJson()) {
            return redirect()->route('owner.services.index')->with('success', 'Đã xóa dịch vụ.');
        }

        return response()->json(['message' => 'Service deleted.']);
    }

    private function validatedService(Request $request, bool $updating = false): array
    {
        $prefix = $updating ? 'sometimes' : 'required';

        return $request->validate([
            'name' => [$prefix, 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'unit' => [$prefix, 'string', 'max:50'],
            'price' => [$prefix, 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:active,inactive'],
        ]);
    }

    private function ownedService(Request $request, ?Service $service): Service
    {
        $owner = $this->actingOwner($request);
        $service ??= Service::query()->findOrFail($request->integer('id'));
        abort_unless($service->owner_id === $owner->id, 404);

        return $service;
    }

    private function owner(Request $request): User
    {
        $user = $request->user();
        if (! $user) {
            if (! $request->expectsJson()) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }

            abort(401, 'Authentication is required.');
        }
        abort_unless($user->role === 'owner', 403, 'Only owners can manage services.');

        return $user;
    }

    /**
     * DEMO MODE: web (trình duyệt) chưa đăng nhập thì dùng owner đầu tiên
     * để test luồng tạo/sửa. JSON API vẫn bắt buộc đăng nhập.
     * Muốn bật lại login: thay actingOwner() bằng owner() ở store/update/destroy.
     */
    private function actingOwner(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'owner', 403, 'Only owners can manage services.');

            return $user;
        }
        if (! $request->expectsJson()) {
            // DEMO MODE tách rõ với production: chỉ khi bật flag mới cho guest thao tác.
            if (! config('app.demo_guest')) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }
            $demo = User::query()->where('role', 'owner')->orderBy('id')->first();
            abort_unless($demo, 401, 'Chưa có tài khoản chủ trọ, vui lòng đăng nhập.');

            return $demo;
        }
        abort(401, 'Authentication is required.');
    }
}
