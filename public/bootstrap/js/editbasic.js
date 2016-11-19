$(function () {

	//城市联动
	var province = '';
	$.each(city, function (i, k) {
		province += '<option value="' + k.name + '" index="' + i + '">' + k.name + '</option>';
	});
	$('select[name=province]').append(province).change(function () {
		var option = '';
		if ($(this).val() == '') {
			option += '<option value="">请选择</option>';
		} else {
			var index = $(':selected', this).attr('index');
			var data = city[index].child;
			for (var i = 0; i < data.length; i++) {
				option += '<option value="' + data[i] + '">' + data[i] + '</option>';
			}
		}

		$('select[name=city]').html(option);
	});

	//所在地默认选项
	address = address.split(' ');
	$('select[name=province]').val(address[0]);
	$.each(city, function (i, k) {//city是一个变量对象
		if (k.name == address[0]) {
			var str = '';
			for (var j in k.child) {
				str += '<option value="' + k.child[j] + '" ';
				if (k.child[j] == address[1]) {
					str += 'selected="selected"';
				}
				str += '>' + k.child[j] + '</option>';
			}
			$('select[name=city]').html(str);
		}
	});

	//星座默认选项
	$('select[name=night]').val(constellation);

});