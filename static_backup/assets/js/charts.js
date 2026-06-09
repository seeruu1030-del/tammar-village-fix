function initCharts() {
    const cashFlowChartEl = document.querySelector("#cashFlowChart");
    if (!cashFlowChartEl) return;

    const chartOptions = {
        series: [{
            name: 'Pemasukan (IPL & Kas)',
            data: [31, 40, 28, 51, 42, 60]
        }, {
            name: 'Tabungan Warga (Terkumpul)',
            data: [11, 32, 45, 32, 34, 52] 
        }],
        chart: {
            height: 300,
            type: 'area',
            fontFamily: 'Inter, sans-serif',
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        colors: ['#0ea5e9', '#10b981'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#64748b' } }
        },
        yaxis: {
            labels: {
                formatter: function (value) { return value + " Jt"; },
                style: { colors: '#64748b' }
            }
        },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: true } } },
        legend: { position: 'top', horizontalAlign: 'right' },
        tooltip: {
            theme: 'light',
            y: { formatter: function (val) { return "Rp " + val + ".000.000" } }
        }
    };
    const chart = new ApexCharts(cashFlowChartEl, chartOptions);
    chart.render();
}
