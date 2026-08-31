// Logistics Pro - Interactive Chart Engine

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('businessChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Data points matching UI screenshot
    const labels = ['01 Aug', '06 Aug', '11 Aug', '16 Aug', '21 Aug', '26 Aug', '31 Aug'];
    const billingData = [210000, 270000, 290000, 260000, 310000, 290000, 390000];
    const expenseData = [120000, 140000, 130000, 150000, 145000, 175000, 240000];

    const maxVal = 400000;
    
    function drawChart() {
        const width = canvas.width = canvas.parentElement.clientWidth;
        const height = canvas.height = 240;
        const paddingLeft = 60;
        const paddingBottom = 35;
        const paddingTop = 20;
        const paddingRight = 20;

        const chartWidth = width - paddingLeft - paddingRight;
        const chartHeight = height - paddingTop - paddingBottom;

        ctx.clearRect(0, 0, width, height);

        // Draw Y-Axis Grid Lines & Labels
        const ySteps = [0, 100000, 200000, 300000, 400000];
        ctx.fillStyle = '#94A3B8';
        ctx.font = '11px Inter, sans-serif';
        ctx.textAlign = 'right';

        ySteps.forEach(step => {
            const y = paddingTop + chartHeight - (step / maxVal) * chartHeight;
            ctx.strokeStyle = '#F1F5F9';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(paddingLeft, y);
            ctx.lineTo(width - paddingRight, y);
            ctx.stroke();

            // Label in Crore
            const formattedLabel = step === 0 ? '0' : '₹' + (step / 100000) + ' Cr';
            ctx.fillText(formattedLabel, paddingLeft - 10, y + 4);
        });

        // Calculate Coordinates
        const getX = (index) => paddingLeft + (index / (labels.length - 1)) * chartWidth;
        const getY = (val) => paddingTop + chartHeight - (val / maxVal) * chartHeight;

        // Draw X-Axis Labels
        ctx.textAlign = 'center';
        labels.forEach((label, idx) => {
            const x = getX(idx);
            ctx.fillText(label, x, height - 10);
        });

        // Function to draw smooth bezier curve
        function drawCurve(data, strokeColor, fillColor, gradientStop) {
            ctx.beginPath();
            ctx.moveTo(getX(0), getY(data[0]));

            for (let i = 0; i < data.length - 1; i++) {
                const x0 = getX(i);
                const y0 = getY(data[i]);
                const x1 = getX(i + 1);
                const y1 = getY(data[i + 1]);

                const mx = (x0 + x1) / 2;
                ctx.bezierCurveTo(mx, y0, mx, y1, x1, y1);
            }

            // Fill area
            if (fillColor) {
                const fillPath = new Path2D();
                fillPath.moveTo(getX(0), getY(data[0]));
                for (let i = 0; i < data.length - 1; i++) {
                    const x0 = getX(i);
                    const y0 = getY(data[i]);
                    const x1 = getX(i + 1);
                    const y1 = getY(data[i + 1]);
                    const mx = (x0 + x1) / 2;
                    fillPath.bezierCurveTo(mx, y0, mx, y1, x1, y1);
                }
                fillPath.lineTo(getX(data.length - 1), paddingTop + chartHeight);
                fillPath.lineTo(getX(0), paddingTop + chartHeight);
                fillPath.closePath();

                const grad = ctx.createLinearGradient(0, paddingTop, 0, paddingTop + chartHeight);
                grad.addColorStop(0, gradientStop);
                grad.addColorStop(1, 'rgba(255, 255, 255, 0)');
                ctx.fillStyle = grad;
                ctx.fill(fillPath);
            }

            // Stroke line
            ctx.strokeStyle = strokeColor;
            ctx.lineWidth = 2.5;
            ctx.stroke();

            // Draw circular dots
            data.forEach((val, idx) => {
                const x = getX(idx);
                const y = getY(val);

                ctx.beginPath();
                ctx.arc(x, y, 4, 0, Math.PI * 2);
                ctx.fillStyle = strokeColor;
                ctx.fill();
                ctx.strokeStyle = '#FFFFFF';
                ctx.lineWidth = 2;
                ctx.stroke();
            });
        }

        // Draw Billing (Blue) and Expenses (Red/Coral)
        drawCurve(billingData, '#2563EB', true, 'rgba(37, 99, 235, 0.12)');
        drawCurve(expenseData, '#EF4444', true, 'rgba(239, 68, 68, 0.08)');
    }

    drawChart();
    window.addEventListener('resize', drawChart);
});
