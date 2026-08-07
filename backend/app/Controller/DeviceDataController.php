<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\DeviceData;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class DeviceDataController extends AbstractController
{
    #[Inject]
    protected ValidatorFactoryInterface $validationFactory;

    /**
     * 列表查询，支持按 device_id 筛选。
     */
    public function index(): array
    {
        $deviceId = (string) $this->request->query('device_id', '');

        $query = DeviceData::query();
        if ($deviceId !== '') {
            $query->where('device_id', $deviceId);
        }

        $list = $query->orderByDesc('id')->get();

        return $this->success($list);
    }

    /**
     * 平台统计数据（仪表盘用）。
     */
    public function stats(): array
    {
        $yesterday = date('Y-m-d H:i:s', time() - 86400);

        return $this->success([
            'device_count' => (int) DeviceData::query()->distinct()->count('device_id'),
            'active_24h' => (int) DeviceData::query()
                ->where('created_at', '>=', $yesterday)
                ->distinct()
                ->count('device_id'),
            'today_count' => (int) DeviceData::query()
                ->where('created_at', '>=', date('Y-m-d 00:00:00'))
                ->count(),
            'total_count' => (int) DeviceData::query()->count(),
            'latest_time' => DeviceData::query()->max('created_at'),
        ]);
    }

    /**
     * 新增设备数据。
     */
    public function store(): array
    {
        $data = $this->validate($this->request->all(), [
            'device_id' => 'required|string|max:64',
            'temp' => 'nullable|numeric|min:-99.99|max:999.99',
            'humidity' => 'nullable|numeric|min:0|max:100',
        ]);

        $model = DeviceData::create($data);
        $model->refresh();

        return $this->success($model, '新增成功');
    }

    /**
     * 修改设备数据（支持只传需要修改的字段）。
     */
    public function update(int $id): array
    {
        $model = $this->findModel($id);

        $data = $this->validate($this->request->all(), [
            'device_id' => 'sometimes|required|string|max:64',
            'temp' => 'sometimes|nullable|numeric|min:-99.99|max:999.99',
            'humidity' => 'sometimes|nullable|numeric|min:0|max:100',
        ]);

        $model->fill($data);
        $model->save();
        $model->refresh();

        return $this->success($model, '修改成功');
    }

    /**
     * 删除设备数据。
     */
    public function delete(int $id): array
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->success(null, '删除成功');
    }

    private function findModel(int $id): DeviceData
    {
        $model = DeviceData::find($id);
        if (! $model) {
            throw new BusinessException(ErrorCode::NOT_FOUND, '数据不存在');
        }
        return $model;
    }

    private function validate(array $data, array $rules): array
    {
        $messages = [
            'device_id.required' => '设备编号不能为空',
            'device_id.string' => '设备编号格式不正确',
            'device_id.max' => '设备编号最长64个字符',
            'temp.numeric' => '温度必须是数字',
            'temp.min' => '温度不能小于 -99.99',
            'temp.max' => '温度不能大于 999.99',
            'humidity.numeric' => '湿度必须是数字',
            'humidity.min' => '湿度不能小于 0',
            'humidity.max' => '湿度不能大于 100',
        ];

        $validator = $this->validationFactory->make($data, $rules, $messages);
        if ($validator->fails()) {
            throw new BusinessException(ErrorCode::PARAM_ERROR, implode('；', $validator->errors()->all()));
        }

        return $validator->validated();
    }

    private function success(mixed $data = null, string $msg = 'success'): array
    {
        return [
            'code' => ErrorCode::SUCCESS,
            'msg' => $msg,
            'data' => $data,
        ];
    }
}
