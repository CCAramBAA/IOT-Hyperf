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
            // 强制确保一定是数组，如果后端返回null/对象，赋值空数组
            this.deviceDataList = Array.isArray(raw) ? raw : [];
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