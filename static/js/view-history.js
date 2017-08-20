// JavaScript Document
ViewHistory = function() {

	this.config = {
		limit: 10,
		storageKey: 'viewHistory',
		primaryKey: 'url'
	};

	this.cache = {
		localStorage:  null,
		userData:  null,
		attr:  null
	};
};

ViewHistory.prototype = {

	init: function(config) {
		this.config = config || this.config;
		var _self = this;

		// define localStorage
		if (!window.localStorage && (this.cache.userData = document.body) && this.cache.userData.addBehavior && this.cache.userData.addBehavior('#default#userdata')) {
			this.cache.userData.load((this.cache.attr = 'localStorage'));

			this.cache.localStorage = {
				'getItem': function(key) {
					return _self.cache.userData.getAttribute(key);
				},
				'setItem': function(key, value) {
					_self.cache.userData.setAttribute(key, value);
					_self.cache.userData.save(_self.cache.attr);
				}
			};

		} else {
			this.cache.localStorage = window.localStorage;
		}
	},

	addHistory: function(item) {
		var items = this.getHistories();
		for(var i=0, len=items.length; i<len; i++) {
			if(item[this.config.primaryKey] && items[i][this.config.primaryKey] && item[this.config.primaryKey] === items[i][this.config.primaryKey]) {
				items.splice(i, 1);
				break;
			}
		}

		items.push(item);

		if(this.config.limit > 0 && items.length > this.config.limit) {
			items.splice(0, 1);
		}

		var json = JSON.stringify(items);
		this.cache.localStorage.setItem(this.config.storageKey, json);
	},

	getHistories: function() {
		var history = this.cache.localStorage.getItem(this.config.storageKey);
		if(history) {
			return JSON.parse(history);
		}
		return [];
	}
};
/* <![CDATA[ */
if(typeof localStorage !== 'undefined' && typeof JSON !== 'undefined') {
    var viewHistory = new ViewHistory();
    viewHistory.init({
        limit: 5,
        storeagekey: 'viewHistory',
        primaryKey: 'url'
    });
}
/* ]]> */
/* <![CDATA[ */
var wrap = document.getElementById('view-history');

// 如果 ViewHistory 的实例存在，并且外层节点存在，则可显示历史浏览记录
if(viewHistory && wrap) {
    // 获取浏览记录
    var histories = viewHistory.getHistories();
	
	//转换时间格式
	var minute = 1000 * 60;
	var hour = minute * 60;
	var day = hour * 24;
	var halfamonth = day * 15;
	var month = day * 30;
function getDateDiff(dateTimeStamp){
	var now = new Date().getTime();
	var diffValue = now - dateTimeStamp;
	if(diffValue < 0){
	 result="刚刚浏览过";
	 }
	var monthC =diffValue/month;
	var weekC =diffValue/(7*day);
	var dayC =diffValue/day;
	var hourC =diffValue/hour;
	var minC =diffValue/minute;
	if(monthC>=1){
	 result=parseInt(monthC) + "个月前浏览过";
	 }
	 else if(weekC>=1){
	 result=parseInt(weekC) + "周前浏览过";
	 }
	 else if(dayC>=1){
	 result=parseInt(dayC) +"天前浏览过";
	 }
	 else if(hourC>=1){
	 result=parseInt(hourC) +"个小时前浏览过";
	 }
	 else if(minC>=1){
	 result=parseInt(minC) +"分钟前浏览过";
	 }else
	 result="刚刚浏览过";
	return result;
	}

    // 组装列表
    var list = document.createElement('ul');
    if(histories && histories.length > 0) {
        for(var i=histories.length-1; i>=0; i--) {
            var history = histories[i];
            var item = document.createElement('li');
            item.innerHTML = '<p class="side-art">'+history.title+'</p><p class="side-meta">'+getDateDiff(history.time)+'</p>';
            list.appendChild(item);
        }

        // 插入页面特定位置
        wrap.appendChild(list);
    }
}
/* ]]> */

//在没有历史记录时，边栏不显示历史记录
jQuery(function(){ 
	var temp = jQuery('#view-history').html();
	if (temp == '') {
	jQuery('#sidhis').hide();
	}
}); 