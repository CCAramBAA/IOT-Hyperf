let chartInstance = null;

function initChart() {
  const dom = document.getElementById('echart-container');
  chartInstance = echarts.init(dom);
}

function renderChart(list) {
  if(!chartInstance) initChart();
  const xAxisData = list.map(i=>i.created_at);
  const tempData = list.map(i=>i.temp);
  const humData = list.map(i=>i.humidity);

  const option = {
    tooltip: {trigger:'axis'},
    xAxis: {type:'category', data:xAxisData},
    yAxis: {type:'value'},
    series: [
      {name:'温度', type:'line', data:tempData},
      {name:'湿度', type:'line', data:humData}
    ]
  }
  chartInstance.setOption(option);
}
