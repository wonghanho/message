<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
	<script type="text/javascript" src="bootstrap-3.3.7-dist/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	<style type="text/css">
		body{
			background: url(images/1.jpg);
		}
	</style>
</head>
<body>
	<div class="container" style="width: 600px;height: auto;">
		<h1 class="text-center text-info">注册</h1>
		<form action="insert.php" method="post">
		  <div class="form-group">
		    <label for="phone">手机号</label>
		    <input type="text" class="form-control" name="phone" id="phone" placeholder="phone">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">密码</label>
		    <input type="password" class="form-control"name="upass" id="exampleInputPassword1" placeholder="Password">
		  </div>
		  <div class="form-group" style="width: 440px;display: inline-block;">
		    <label for="code">验证码</label>
		    <input type="text" class="form-control" name="code" id="code" placeholder="code">
		  </div>
		 	<input type="button" onclick="sendPhone(this)" id="sendBtn" value="免费获取验证码" class="btn btn-success">
		  <button type="submit" class="btn btn-info form-control">注册</button>
		</form>		
	</div>

	<script type="text/javascript">
		function editCon()
		{
			var t = 60;
			var time = null;
			if(time == null){
				time = setInterval(function(){
					t--;
					// 修改当前button 和 内容
					$('#sendBtn').val('重新发送('+t+'s)');
					if(t < 1){
						// 清除定时器
						clearInterval(time);
						time = null;
						$('#sendBtn').val('免费获取验证码');
						// 设置button状态
						$('#sendBtn').attr('disabled',false);
					}
				},1000);
			}
				
		}

		function sendPhone(obj)
		{
			// 接收手机号码
			var phone = $('#phone').val();
			// 定义正则检查手机号是否格式正确
			var phone_grep = /^1{1}[3456789]{1}[0-9]{9}$/;
			// 使用正则检查手机号
			if(!phone_grep.test(phone)){
				return false;
			}

			// 将js对象转化成jquery对象
			var object = $(obj);
			// 设置button状态
			object.attr('disabled',true);
			// 获取当前的按钮上的文字
			var text = object.val();
			// alert(obj);[object HTMLInputElement]  js对象
			// alert($(obj));[object Object]  jquery对象
			if(text == '免费获取验证码'){
				// 发送ajax 请求后台 
				$.get('sendPhone.php',{'phone':phone},function(data){
					if(data.code == 0){
						editCon();
					}else{
						alert(data.msg);
					}
				},'json');	
			}else{
				return false;
			}
			
		}

	</script>
</body>
</html>