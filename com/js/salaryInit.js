

function updateChart(time, amount){
    var lineChart = $('#line-chart');
    while(time.length < 12){
        time.push('暂无数据');
    }
    while(amount.length < 12){
        amount.push(0);
    }

    if (lineChart.length > 0) {
        if(time.length > 0){
            new Chart(lineChart, {
                type: 'line',
                data: {
                    labels: time.slice(-12),
                    datasets: [{
                        label: 'salary amount ￥',
                        data: amount.slice(-12),
                        backgroundColor: 'rgba(66, 165, 245, 0.5)',
                        borderColor: '#2196F3',
                        borderWidth: 1
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    }
}

function modifyPassword(){
    var password = prompt('请输入旧密码');
    $.ajax({
        type: 'POST',      //data 传送数据类型。post 传递
        url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
        data: {
            'command':'validatePassword',
            'password': password,

        },  //传送的数据   
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            if(data == 1){
                var newPassword = prompt('请输入新密码');
                $.ajax({
                    type: 'POST',      //data 传送数据类型。post 传递
                    url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                    data: {
                        'command':'updatePassword',
                        'password': newPassword,

                    },  //传送的数据   
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function (data) {
                        if(data == 1){
                            alert('密码更改成功');
                        }
                        else{
                            alert('密码更改失败');
                        }
                    },
                    error:function(){
                        alert('数据传输错误');
                    }
                });
            }
            else{
                alert('密码输入错误');
            }
        },
        error:function(){
            alert('数据传输错误');
        }
    });
}
function validatePassword(password){
    var ret;
    $.ajax({
        type: 'POST',      //data 传送数据类型。post 传递
        url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
        data: {
            'command':'validatePassword',
            'password': password,

        },  //传送的数据   
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            alert(data);
            ret = data;
        },
        error:function(){
            alert('数据传输错误');
        }
    });
    return ret;
}
function updatePassword(password){
    var ret;
    $.ajax({
        type: 'POST',      //data 传送数据类型。post 传递
        url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
        data: {
            'command':'updatePassword',
            'password': password,

        },  //传送的数据   
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            alert(data);
            ret = data;
        },
        error:function(){
            alert('数据传输错误');
        }
    });
    return ret;
}
