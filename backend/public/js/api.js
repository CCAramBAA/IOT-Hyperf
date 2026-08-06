const api = {
  async getDeviceData(deviceId) {
    const res = await fetch(`/device/data?device_id=${deviceId}`);
    return await res.json();
  }
}