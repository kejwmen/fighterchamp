function isLicence(data) {
	return data.isLicence ? 'Licencja PZB' : ''
}

function fightResult(userFight) {
	
	var result = '';
	
	if (userFight.result === 'win' || userFight.result === 'win_ko') {
		result = '<span class="label label-success">W</span>';
	} else if (userFight.result === 'lose') {
		result = '<span class="label label-danger">L</span>'
	} else if (userFight.result === 'draw') {
		result = '<span class="label label-warning">D</span>';
	} else if (userFight.result === 'disqualify') {
		result = '<span class="label label-dark">DQ</span>';
	}
	
	return result;
	
}


function record(user) {
	return '<span style="color: #5cb85c">' + user.record.win + '</span> ' +
		'<span style="color: #f0ad4e">' + user.record.draw + '</span> ' +
		'<span style="color: #d9534f">' + user.record.lose + '</span>'
}


function club(user) {
	if (user.club === null) {
		return '';
	}
	return "<br><a href='" + user.club.href + "'>" + user.club.name + "</a>";
}

function age(user) {
	if (user.birthDay === null) {
		return '';
	}
	
	// var age = new Date(new Date - new Date(user.birthDay.date)).getFullYear() - 1970;
	
	var age = (new Date).getFullYear() - (new Date(user.birthDay.date)).getFullYear();
	
	var temp = '';
	if(age <= 13) {
		temp = '(adept)';
	}else if(age == 14){
		temp = '(młodzik)';
	}else if(age == 15 || age == 16){
		temp = '(kadet)';
	}else if(age == 17 || age == 18){
		temp = '(junior)';
	}else if(age >= 19 && age <= 23 ){
		temp = '(młodzieżowiec)';
	}else if(age > 23){
		temp = '(senior)';
	}
	
	return age + ' lat ' + temp;
}