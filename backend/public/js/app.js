const { createApp } = Vue;

createApp({
  data(){
    return {
      queryParams:{
        deviceId:"dev001"
      },
      deviceDataList:[]
    }
  },
  methods:{
    async fetchDeviceData(){
        try{
            const raw = await api.getDeviceData(this.queryParams.deviceId);
            // 后端统一返回 { code, msg, data }，列表接口 data 为 { list, total, ... }
            const payload = (raw && raw.code === 200 && raw.data) ? raw.data : null;
            this.deviceDataList = (payload && Array.isArray(payload.list)) ? payload.list : [];
            renderChart(this.deviceDataList);
        }catch(e){
            console.error("请求错误",e);
            alert("接口请求失败，请确认Hyperf后端接口 /device/data 已经开发完成");
            this.deviceDataList = [];
        }
    }
  },
  mounted(){
    initChart();
  }
}).use(Vuetify.createVuetify())
.mount("#app");
