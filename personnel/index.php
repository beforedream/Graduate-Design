<?php
    header('Access-Control-Allow-Origin:' . $_SERVER['HTTP_ORIGIN']);
    //跨域且使用session时不能使用 *
    header("Access-Control-Allow-Credentials: true" );
    function convertUrlQuery($query){
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }

    function logout($logoutUrl, $ticket, $localHost){
        echo "<script language=\"javascript\">";
        echo "alert(\"redirect to $logoutUrl\");
                window.location.href=\"$logoutUrl?page=$localHost&ticket=$ticket\";";
        echo "</script>";
        //header("Location: " . $loginUrl);
    }
    
    session_start();
    $servername = "localhost";
    $username = "root";
    $passwd = "root";
    $dbname = "EMS";
    $conn = new mysqli($servername, $username, $passwd, $dbname);
    if ($conn->connect_error) {
        echo "连接失败: " . $conn->connect_error;
    }
    $localHost = $_SERVER['HTTP_HOST'];
    $loginUrl = "https://www.login.com/login.php";
    $logoutUrl = "https://www.login.com/logout.php";
    $dispatchUrl = "https://www.login.com/dispatch.php";
    $param = $_SERVER['QUERY_STRING'];
    $params = convertUrlQuery($param);
    if(isset($_SESSION['ticket'])){
        $ticket = $_SESSION['ticket'];
    }
    else{
        $ticket = $params["ticket"];
    }
    if($ticket == null){
        echo "<script> window.location.href='$loginUrl?page=$localHost'</script>";
    }
?>

<!doctype html>
<html lang="en">
<head>
    <input id='ticket' style='display:none' value=<?php echo $ticket?>>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carbon - Admin Template</title>
    <link rel="stylesheet" href="./com/vendor/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="./com/vendor/font-awesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="./com/css/styles.css">
    <script src="./com/vendor/jquery/jquery.min.js"></script>
    <script src="./com/vendor/popper.js/popper.min.js"></script>
    <script src="./com/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="./com/vendor/chart.js/chart.min.js"></script>
    <script src="./com/js/carbon.js"></script>
    <script src="./com/js/personnel.js"></script>
    <script>
        $.ajax({
            type: 'POST',      //data 传送数据类型。post 传递
            url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
            data: {
                'command':'validate',
                'ticket': <?php echo $ticket?>,

            },  //传送的数据   
            xhrFields: {
                withCredentials: true
            },
            success: function (data) {
                ret = data;
                if(window.console){
                    console.log(data);
                }
                if(data == 0){
                    // alert(data);
                    alert('没有访问权限, 请重新登录');
                    window.location.href = 'dispatch.php?command=logout';
                }
            },
            error:function(){
                alert('validate数据传输错误');
            }
        });

        function updateChart(time, amount){
            while(time.length < 12){
                time.push('暂无数据');
            }
            while(amount.length < 12){
                amount.push(0);
            }
        
            if ($('#line-chart').length > 0) {
                $('#line-chart').remove(); // this is my <canvas> element
                $('#canvasParent').append('<canvas id="line-chart" width="100%" height="20"></canvas>');
            }
            var lineChart = $('#line-chart');
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
        function maxArray(array){
            var max = 0;
            var index = 0;
            if(array.length > 0){
                max = array[0];
                index = 0;
                for(var i = 0; i < array.length; i++){
                    if(array[i] > max){
                        max = array[i];
                        index = i;
                    }
                }
            }
            else{
                index = -1;
            }
            return index;
        }
        function minArray(array){
            var min = 0;
            var index = 0;
            if(array.length > 0){
                min = array[0];
                index = 0;
                for(var i = 0; i < array.length; i++){
                    if(array[i] < min){
                        min = array[i];
                        index = i;
                    }
                }
            }
            else{
                index = -1;
            }
            return index;
        }
        function sumArray(array){
            var sum = 0;
            for(var i = 0; i < array.length; i++){
                sum += eval(array[i]);
            }
            return sum;
        }
        function departmentPersonnelSelect(){
            var personnelDepartment = $('#personnelDepartment');
            $.ajax({
                type: 'POST',      //data 传送数据类型。post 传递
                url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                data: {
                    'command':'departmentPersonnelSelect',
                    'DID': personnelDepartment.val(),
                    'ticket': <?php echo $ticket?>,
                },  
                xhrFields: {    
                    withCredentials: true
                },
                success: function (data) {
                    $("#table  tr:not(:first)").remove();
                    var length = eval(data)[0];
                    var DID = eval(data)[1];
                    var EID = eval(data)[2];
                    var Ename = eval(data)[3];
                    var Esex = eval(data)[4];
                    var Eage = eval(data)[5];
                    var Eemail = eval(data)[6];
                    var Ephone = eval(data)[7];
                    // var Dname = eval(data)[1];
                    for(var i = 0; i < length; i++){
                        var tr = '';
                        tr += "<tr>";
                        tr += "<td><input type='checkbox' name='check'/></td>";
                        tr += "<td>" + '<select class="font-weight-light" id="personnelDepartmentTable' + i + '" style="width:100%; height:25px" required>'
                                 +  '<?php
                                        $sql = "select DID, Dname from department order by DID asc;";
                                        $result = $conn->query($sql);
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                echo "<option value=". $row["DID"] .">". $row["Dname"]. "</option>";
                                            }
                                        }
                                    ?>'
                                 + ' </select>' + "</td>";
                        tr += "<td><div>" + EID[i] + "</div></td>";
                        tr += "<td>" + Ename[i] + "</td>";
                        tr += "<td>" 
                            + '<select class="font-weight-light" id="personnelEsexTable' + i + '" style="width:100%; height:25px" required>'
                            + '<option value=1 >男</option>'
                            + '<option value=0 >女</option>'
                            + '</select>' 
                            + "</td>";
                        
                        tr += "<td>" + Eage[i] + "</td>";
                        tr += "<td>" + Eemail[i] + "</td>";
                        tr += "<td>" + Ephone[i] + "</td>";
                        tr += "<td disabled><button onclick = 'ModifyEmployee($(this));'>修改</button></td>"
                        tr += "</tr>";
                        $("#table tbody").append(tr);
                        $("#personnelDepartmentTable" + i).val(DID[i]);
                        $("#personnelEsexTable" + i).val(Esex[i]);
                    }
                    $("#table tr:gt(0)").hover( function () {
                        $(this).addClass("hover");
                    },
                    function () { 
                        $(this).removeClass("hover");
                    });
                },
                error:function(){
                    alert('departmentPersonnelSelect数据传输错误');
                }
            });
        }
        function employeePersonnelSelect(){
            var personnelEID = $('#personnelEID');
            if(personnelEID.val() == ''){
                alert('请输入工号');
                return;
            }
            $.ajax({
                type: 'POST',      //data 传送数据类型。post 传递
                url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                data: {
                    'command':'employeePersonnelSelect',
                    'EID': personnelEID.val(),
                    'ticket': <?php echo $ticket?>,
                },  
                xhrFields: {    
                    withCredentials: true
                },
                success: function (data) {
                    $("#table  tr:not(:first)").remove();
                    var length = eval(data)[0];
                    var DID = eval(data)[1];
                    var EID = eval(data)[2];
                    var Ename = eval(data)[3];
                    var Esex = eval(data)[4];
                    var Eage = eval(data)[5];
                    var Eemail = eval(data)[6];
                    var Ephone = eval(data)[7];
                    // var Dname = eval(data)[1];
                    for(var i = 0; i < length; i++){
                        var tr = '';
                        tr += "<tr>";
                        tr += "<td><input type='checkbox' name='check'/></td>";
                        tr += "<td>" + '<select class="font-weight-light" id="personnelDepartmentTable' + i + '" style="width:100%; height:25px" required>'
                                 +  '<?php
                                        $sql = "select DID, Dname from department order by DID asc;";
                                        $result = $conn->query($sql);
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                echo "<option value=". $row["DID"] .">". $row["Dname"]. "</option>";
                                            }
                                        }
                                    ?>'
                                 + ' </select>' + "</td>";
                        tr += "<td><div>" + EID[i] + "</div></td>";
                        tr += "<td>" + Ename[i] + "</td>";
                        tr += "<td>" 
                            + '<select class="font-weight-light" id="personnelEsexTable' + i + '" style="width:100%; height:25px" required>'
                            + '<option value=1 >男</option>'
                            + '<option value=0 >女</option>'
                            + '</select>' 
                            + "</td>";
                        
                        tr += "<td>" + Eage[i] + "</td>";
                        tr += "<td>" + Eemail[i] + "</td>";
                        tr += "<td>" + Ephone[i] + "</td>";
                        tr += "<td disabled><button onclick = 'ModifyEmployee($(this));'>修改</button></td>"
                        tr += "</tr>";
                        $("#table tbody").append(tr);
                        $("#personnelDepartmentTable" + i).val(DID[i]);
                        $("#personnelEsexTable" + i).val(Esex[i]);
                    }
                    $("#table tr:gt(0)").hover( function () {
                        $(this).addClass("hover");
                    },
                    function () { 
                        $(this).removeClass("hover");
                    });
                },
                error:function(){
                    alert('departmentPersonnelSelect数据传输错误');
                }
            });
        }
        function ModifyEmployee(self){
            var tr = self.parents("tr");
            var td = tr.children("td");
            var DID = td.eq(1).find("select").val();
            var EID = td.eq(2).text();
            var Ename = td.eq(3).text();
            var Esex = td.eq(4).find("select").val();
            var Eage = td.eq(5).text();
            var Eemail = td.eq(6).text();
            var Ephone = td.eq(7).text();
            $.ajax({
                type: 'POST',      //data 传送数据类型。post 传递
                url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                data: {
                    'command':'insertEmployee',
                    'ticket': <?php echo $ticket?>,
                    'DID': DID,
                    'EID': EID,
                    'Ename': Ename,
                    'Esex': Esex,
                    'Eage': Eage,
                    'Eemail': Eemail,
                    'Ephone': Ephone,
                },  
                xhrFields: {
                    withCredentials: true
                },
                success: function (data) {
                    if(data == 1){
                        alert("员工添加(修改)成功");
                    }
                    else{
                        alert("员工添加(修改)失败");
                    }
                },
                error:function(){
                    alert('insertEmployee数据传输错误');
                }
            });
        }
        function insertEmployee(){
            $("#table  tr:not(:first)").remove();
            var i = 0;
            var tr = '';
            tr += "<tr>";
            tr += "<td><input type='checkbox' name='check'/></td>";
            tr += "<td>" + '<select class="font-weight-light" id="personnelDepartmentTable' + i + '" style="width:100%; height:25px" required>'
                        +  '<?php
                            $sql = "select DID, Dname from department order by DID asc;";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0){
                                while($row = $result->fetch_assoc()){
                                    echo "<option value=". $row["DID"] .">". $row["Dname"]. "</option>";
                                }
                            }
                        ?>'
                        + ' </select>' + "</td>";
            tr += "<td><div title = '默认值：0（自动分配工号）'>" + '0' + "</div></td>";
            tr += "<td>" + "defaultEmployeeName" + "</td>";
            tr += "<td>" 
                + '<select class="font-weight-light" id="personnelEsexTable' + i + '" style="width:100%; height:25px" required>'
                + '<option value=1 >男</option>'
                + '<option value=0 >女</option>'
                + '</select>' 
                + "</td>";
            
            tr += "<td>" + 20 + "</td>";
            tr += "<td>" + "default@company.com" + "</td>";
            tr += "<td title = 'defaultEmployeePhone'>" + "13300000000" + "</td>";
            tr += "<td disabled><button onclick = 'ModifyEmployee($(this));'>确认</button></td>"
            tr += "</tr>";
            $("#table tbody").append(tr);
            $("#personnelDepartmentTable" + i).val(1);
            $("#personnelEsexTable" + i).val(1);
        }
        function deleteEmployee(){
            var table = $('#table tbody');
            var trList = table.children('tr');
            var tableLength = trList.length;
            var EIDArray = new Array();
            var j = 0;
            for(var i = 1; i < tableLength; i++){
                var tdList = trList.eq(i).children('td');
                if(tdList.eq(0).children()[0].checked){
                    EIDArray[j] = tdList.eq(2).text();
                    j++;
                }
            }
            if(EIDArray.length == 0){
                alert('请选择待删除员工信息');
                return;
            }
            $.ajax({
                type: 'POST',      //data 传送数据类型。post 传递
                url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                data: {
                    'command':'deleteEmployee',
                    'ticket': <?php echo $ticket?>,
                    'EID': JSON.stringify(EIDArray),
                },  
                xhrFields: {
                    withCredentials: true
                },
                success: function (data) {
                    if(data == 1){
                        alert('删除成功');
                    }
                    else{
                        alert('删除失败');
                    }
                },
                error:function(){
                    alert('deleteRecord数据传输错误');
                }
            });
        }

        function selectAll(selectStatus){//传入参数（全选框的选中状态）
            //根据name属性获取到单选框的input，使用each方法循环设置所有单选框的选中状态
            if(selectStatus){
                $("input[name='check']").each(function(i,n){
                    n.checked = true;
                });
            }
            else{
                $("input[name='check']").each(function(i,n){
                    n.checked = false;
                });
            }
        }
    </script>
    <style> 
        .class_1{ 
            display: none; 
            position: absolute; 
            top: 0%; 
            left: 0%; 
            width: 100%; 
            height: 100%; 
            background-color: black; 
            z-index:1001; 
            -moz-opacity: 0.8; 
            opacity:.80; 
            filter: alpha(opacity=88); 
        } 
        .class_2 { 
            display: none; 
            position: absolute; 
            top: 25%; 
            left: 25%; 
            width: 55%; 
            height: 55%; 
            padding: 20px; 
            border: 2px solid orange; 
            background-color: white; 
            z-index:1002; 
            overflow: auto; 
        } 

        .title_box{
            width:100%;
            overflow:hidden;
            display=flex;
        }
        .title_box input{
            width:100%;
            border:none;
        }
        .title_box select{
            border:none;
        }
        .title_box option{
            border:none;
        }
        table { 
            border-collapse: collapse; 
            border-spacing: 0; 
            margin-right: auto; 
            margin-left: auto; 
            width: 100%;
        } 
        th, td { 
            border: 1px solid #b5d6e6; 
            font-size: 12px; 
            font-weight: normal; 
            text-align: center; 
            vertical-align: middle; 
            height: 30px;
        } 
        th { 
            background-color: Gray;
        } 
        .hover { 
            background-color: #cccc00;
        }
    </style> 
</head>
<body class="sidebar-fixed header-fixed">
<div class="page-wrapper">

    <div id = 'message' class = 'class_2'>
        <div class="row">
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="title_box">
                            <span class="h4 d-block font-weight-normal mb-2">姓名</span>
                            <input class="font-weight-light" id='Ename' type='text' value="<?php echo $_SESSION['Ename']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">性别</span>
                            <select class="font-weight-light" id='Esex' disabled required>
                            <option value=1 <?php if($_SESSION['Esex']) echo 'selected'?>>男</option>
                            <option value=0 <?php if(!$_SESSION['Esex']) echo 'selected'?>>女</option>
                            </select>
                        </div>
                    </div>style
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">年龄</span>
                            <input class="font-weight-light" id='Eage' type='text' value="<?php echo $_SESSION['Eage']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>style
                            <span class="h4 d-block font-weight-normal mb-2">邮件</span>
                            <input class="font-weight-light" id='Eemail' type='text' value="<?php echo $_SESSION['Eemail']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">部门</span>
                            <input class="font-weight-light" id='Dname' type='text' value="<?php echo $_SESSION['Dname']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">主管</span>
                            <input class="font-weight-light" id='MEname' type='text' value="<?php echo $_SESSION['MEname']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">工号</span>
                            <input class="font-weight-light" id='EID' type='text' value="<?php echo $_SESSION['EID']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class='title_box'>
                            <span class="h4 d-block font-weight-normal mb-2">手机</span>
                            <input class="font-weight-light" id='Ephone' type='text' value="<?php echo $_SESSION['Ephone']?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="postion:fixed; bottom:5px; float:right;">
            <button type='button' style="width:50px" onclick="
                document.getElementById('Ename').readOnly=false;
                document.getElementById('Esex').disabled=false;
                document.getElementById('Eage').readOnly=false;
                document.getElementById('Eemail').readOnly=false;
                document.getElementById('Ephone').readOnly=false;

                document.getElementById('Ename').style.border = '1px solid black';
                document.getElementById('Esex').style.border = '1px solid black';
                document.getElementById('Eage').style.border = '1px solid black';
                document.getElementById('Eemail').style.border = '1px solid black';
                document.getElementById('Ephone').style.border = '1px solid black';
            ">修改</button>
            <script>
                function UpdateDB(sql){
                    $.ajax({
                        type: 'POST',      //data 传送数据类型。post 传递
                        url: 'https://www.login.com/dispatch.php',  // yii 控制器/方法  
                        data: {
                            'command':'update',
                            'sql':sql,
                        },  //传送的数据   
                        xhrFields: {
                            withCredentials: true
                        },
                        success: function (data) {
                            // alert(data);
                            url =  'https://www.login.com/login.php?page='+window.location.host;
                            window.location.href = url;
                        },
                        error:function(){
                            alert('数据库更新失败');
                        }
                    });
                }
            </script>
            <button type='button' style="width:50px" onclick="
                if(confirm('确认修改么？')){
                    var Ename = document.getElementById('Ename').value;
                    var Esex = document.getElementById('Esex').value;
                    var Eage = document.getElementById('Eage').value;
                    var Eemail = document.getElementById('Eemail').value;
                    var Ephone = document.getElementById('Ephone').value;
                    sql = 'update employee set Ename = \'' + Ename + 
                    '\', Esex = ' + Esex + ', Eage = ' + Eage + 
                    ', Eemail = \'' + Eemail + '\', Ephone = \'' + 
                    Ephone + '\' where EID = \'<?php echo $_SESSION['EID']?>\'';
                    UpdateDB(sql);
                    document.getElementById('Ename').readOnly=true;
                    document.getElementById('Esex').disabled=true;
                    document.getElementById('Eage').readOnly=true;
                    document.getElementById('Eemail').readOnly=true;
                    document.getElementById('Ephone').readOnly=true;
                    document.getElementById('Ename').style.border = 'none';
                    document.getElementById('Esex').style.border = 'none';
                    document.getElementById('Eage').style.border = 'none';
                    document.getElementById('Eemail').style.border = 'none';
                    document.getElementById('Ephone').style.border = 'none';
                }
                else{

                }
            ">确认</button>
            <button type='button' style="width:50px" onclick="
                document.getElementById('Ename').value = '<?php echo $_SESSION['Ename']?>';
                document.getElementById('Esex').value = '<?php echo $_SESSION['Esex']?>';
                document.getElementById('Eage').value = '<?php echo $_SESSION['Eage']?>';
                document.getElementById('Eemail').value = '<?php echo $_SESSION['Eemail']?>';
                document.getElementById('Ephone').value = '<?php echo $_SESSION['Ephone']?>';
                document.getElementById('Ename').readOnly=true;
                document.getElementById('Esex').disabled=true;
                document.getElementById('Eage').readOnly=true;
                document.getElementById('Eemail').readOnly=true;
                document.getElementById('Ephone').readOnly=true;
                document.getElementById('Ename').style.border = 'none';
                document.getElementById('Esex').style.border = 'none';
                document.getElementById('Eage').style.border = 'none';
                document.getElementById('Eemail').style.border = 'none';
                document.getElementById('Ephone').style.border = 'none';
            ">取消</button>
            <button type='button' style="width:50px" onclick="
                document.getElementById('Ename').value = '<?php echo $_SESSION['Ename']?>';
                document.getElementById('Esex').value = '<?php echo $_SESSION['Esex']?>';
                document.getElementById('Eage').value = '<?php echo $_SESSION['Eage']?>';
                document.getElementById('Eemail').value = '<?php echo $_SESSION['Eemail']?>';
                document.getElementById('Ephone').value = '<?php echo $_SESSION['Ephone']?>';
                document.getElementById('Ename').readOnly=true;
                document.getElementById('Esex').disabled=true;
                document.getElementById('Eage').readOnly=true;
                document.getElementById('Eemail').readOnly=true;
                document.getElementById('Ephone').readOnly=true;
                document.getElementById('Ename').style.border = 'none';
                document.getElementById('Esex').style.border = 'none';
                document.getElementById('Eage').style.border = 'none';
                document.getElementById('Eemail').style.border = 'none';
                document.getElementById('Ephone').style.border = 'none';
                document.getElementById('message').style.display='none';
            ">退出</button>
        </div>
    </div>
    
    <nav class="navbar page-header">
        <a href="#" class="btn btn-link sidebar-mobile-toggle d-md-none mr-auto">
            <i class="fa fa-bars"></i>
        </a>

        <a class="navbar-brand" href="#">
            <!-- <img src="./com/imgs/logo.png" alt="logo"> -->
            <span>CS1505_litao</span>
        </a>

        <a href="#" class="btn btn-link sidebar-toggle d-md-down-none">
            <i class="fa fa-bars"></i>
        </a>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-md-down-none">
                <a href="#">
                    <i class="fa fa-bell"></i>
                    <span class="badge badge-pill badge-danger"></span>
                </a>
            </li>

            <li class="nav-item d-md-down-none">
                <a href="#">
                    <i class="fa fa-envelope-open"></i>
                    <span class="badge badge-pill badge-danger"></span>
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="./com/imgs/avatar-1.png" class="avatar avatar-sm" alt="logo">
                    <span class="small ml-1 d-md-down-none"><?php echo ''.$_SESSION['EID'].'@'.$_SESSION['Ename']?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header">Account</div>
                    <a href="#" class="dropdown-item" onclick="modifyPassword()">
                         <i class="fa fa-user"></i>Modify Password
                    </a>
                    <div class="dropdown-item" id='Messages' onclick="document.getElementById('message').style.display='block'" >
                        <i class="fa fa-envelope"></i> Messages
                    </div>

                    <div class="dropdown-header">Settings</div>

                    <a href="dispatch.php?page=<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'&'.'command=logout';?>" class="dropdown-item">
                        <i class="fa fa-lock"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <div class="main-container">
        <div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li class="nav-title">Navigation</li>

                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">
                            <i class="icon icon-speedometer"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-title">More</li>

                    <li class="nav-item nav-dropdown">
                        <a href="#" class="nav-link nav-dropdown-toggle">
                            <i class="icon icon-umbrella"></i> Pages <i class="fa fa-caret-left"></i>
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="https://www.personnel.com" class="nav-link">
                                    <i class="icon icon-umbrella"></i> Personnel Page
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="https://www.employee.com" class="nav-link">
                                    <i class="icon icon-umbrella"></i> Employee Page
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card p-4">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4 font-weight-normal mb-2">部门 :</span>
                                    <select class="font-weight-light" id='personnelDepartment'  style="width:100%; height:25px" required>
                                    <?php
                                        $sql = "select DID, Dname from department order by DID asc;";
                                        $result = $conn->query($sql);
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                echo "<option value=". $row['DID'] .">". $row['Dname']. "</option>";
                                            }
                                        }
                                    ?> 
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card p-4">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4 font-weight-normal mb-2">工号 :</span>
                                    <input id = 'personnelEID' style="width : 100%" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card p-4">
                            <div class="d-flex justify-content-between align-items-center" style="
                                padding-top: 0.5rem;
                                padding-left: 2rem;
                                padding-bottom: 0.5rem;
                            }">
                                <button id="departmentSelect" style="
                                    margin-top: 0px;
                                    height: 27px;
                                    width: 75%;
                                "
                                onclick = 'departmentPersonnelSelect();'>部门查询</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center" style="
                                padding-top: 0.5rem;
                                padding-left: 2rem;
                                padding-bottom: 0.5rem;
                            }">
                                <button id="selfSelect" style="
                                    margin-top: 0px;
                                    height: 27px;
                                    width: 75%;
                                "
                                onclick = 'employeePersonnelSelect();'>个人查询</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card p-4">
                            <div class="d-flex justify-content-between align-items-center" style="
                                padding-top: 0.5rem;
                                padding-left: 2rem;
                                padding-bottom: 0.5rem;
                            }">
                                <button id="insertRecord" style="
                                    margin-top: 0px;
                                    height: 27px;
                                    width: 75%;
                                "
                                onclick = "insertEmployee();">插入员工</button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center" style="
                                padding-top: 0.5rem;
                                padding-left: 2rem;
                                padding-bottom: 0.5rem;
                            }">
                                <button id="delectRecord" style="
                                    margin-top: 0px;
                                    height: 27px;
                                    width: 75%;
                                "
                                onclick = 'deleteEmployee();'>删除员工</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header" id = 'chart'>
                                部门员工信息
                            </div>  

                            <div class="card-body p-0">
                                <div class="p-4" id = 'canvasParent'>
                                    <?php
                                        $sql = "select Amount, PayTime from salary where EID = ".$_SESSION['EID']." order by PayTime asc;";
                                        $result = $conn->query($sql);
                                        $income = '';
                                        $time = array();
                                        $amount = array();
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                array_push($time, $row['PayTime']);
                                                array_push($amount, $row['Amount']);
                                            }
                                        }
                                    ?> 
                                    <input id = 'amount' style='display:none' value=<?php echo json_encode($amount)?>>
                                    <input id = 'time' style='display:none' value=<?php echo json_encode($time)?>>
                                    <input style="display:none" type="text" id="ticket" value="<?php echo $_SESSION['ticket']?>">
                                    <table border="1" summary="Monthly savings for the Flintstones family" id = 'table'>
                                        <tr>
                                            <th><input type="checkbox" onclick="selectAll(this.checked)" /></th>
                                            <th>部门</th>
                                            <th>工号</th>
                                            <th>姓名</th>
                                            <th>性别</th>
                                            <th>年龄</th>
                                            <th>邮件</th>
                                            <th>电话</th>
                                            <th>修改</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
