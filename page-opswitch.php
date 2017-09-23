<?php 
/* Template Name: 光功率转换小工具 */
get_header(); 
?>
<div id="main" class="fullwidth">
    <div id="content">
        <div id="container" class="tools">
        	<p class="head-tip">我叫王大锤，是一个小工具</p>
            <div class = "tool-box clearfix">
                <h2>光功率单位换算</h2>
                <p class="tips">请在等式一边输入数字，另一边会实时显示换算结果。</p>
                <p class="exm">例如：2.38, 0x0A, 1E-10</p>
                <div class = "tool-flex">
             	   <input id="dbm"/><span>dbm =&nbsp;</span><input id = "mw"/><span>mW</span>
                </div>
            </div>
            <div class = "tool-box clearfix">
                <h2>波长/频率换算</h2>
                <p class="tips">请在等式一边输入数字，另一边会实时显示换算结果。</p>
                <p class="exm">例如：2.38, 0x0A, 1E-10</p>
                <div class = "tool-flex">
             	   <input id="nm"/><span>nm =&nbsp;</span><input id = "thz"/><span>THz</span>
                </div>
            </div>
            <div class = "tool-box clearfix">
                <h2>光强(OP)/ER/OMA换算(NRZ)</h2>
                <p class="tips">点击对应按钮，通过其它两个参数计算结果。(单位：dBm&amp;dB)</p>
                <p class="exm">例如：2.38, 0x0A, 1E-10</p>
                <ul class="coma tool-flex">
                	<li>
                    	<p class="m-title">OP</p>
                    	<p class="m-input"><input id="op"/><span>dBm</span></p>
                        <button id="bop" type="button">Get</button>
                    </li>
                    <li>
                    	<p class="m-title">OMA</p>
                    	<p class="m-input"><input id="oma"/><span>dBm</span></p>
                        <button id = "boma" type="button">Get</button>
                    </li>
                    <li>
                    	<p class="m-title">ER</p>
                    	<p class="m-input"><input id="er"/><span>dB</span></p>
                        <button id = "ber" type="button">Get</button>
                    </li>
                </ul>
                <p class="error"></p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(function() {
		$('html').attr('style','height: 100%');
		/* Trans dBm -> mW */
		$('#dbm').change(function(){
			var _self = $(this);
			var dBm_Val = $.trim(_self.val());
			if (dBm_Val == '') var mW_Val = '';
			else {
				dBm_Val = Number(dBm_Val);
				if (isNaN(dBm_Val)) var mW_Val = 'Err: Not a Number';
				else {
					var mW_Val = Math.pow(10, (dBm_Val/10));
					mW_Val = Math.round(100000000*mW_Val)/100000000;
				}
			}
			$('#mw').val(mW_Val);
		});
		/* Trans mW -> dBm */
		$('#mw').change(function(){
			var _self = $(this);
			var mW_Val = $.trim(_self.val());
			if (mW_Val == '') var dBm_Val = '';
			else {
				mW_Val = Number(mW_Val);
				if (isNaN(mW_Val)) var dBm_Val = 'Err: Not a Number';
				else {
					var dBm_Val = 10*(Math.log(mW_Val)/Math.LN10);
					dBm_Val = Math.round(100000000*dBm_Val)/100000000;
				}
			}
			$('#dbm').val(dBm_Val);
		});
		
		/* Trans nm -> THz */
		$('#nm').change(function(){
			var _self = $(this);
			var nm_Val = $.trim(_self.val());
			if (nm_Val == '') var thz_Val = '';
			else {
				nm_Val = Number(nm_Val);
				if (isNaN(nm_Val)) var thz_Val = 'Err: Not a Number';
				else {
					var thz_Val = 299792.458/nm_Val;
					thz_Val = Math.round(100000000*thz_Val)/100000000;
				}
			}
			$('#thz').val(thz_Val);
		});
		/* Trans THz -> nm */
		$('#thz').change(function(){
			var _self = $(this);
			var thz_Val = $.trim(_self.val());
			if (thz_Val == '') var nm_Val = '';
			else {
				thz_Val = Number(thz_Val);
				if (isNaN(thz_Val)) var nm_Val = 'Err: Not a Number';
				else {
					var nm_Val = 299792.458/thz_Val;
					nm_Val = Math.round(100000000*nm_Val)/100000000;
				}
			}
			$('#nm').val(nm_Val);
		});
		
		/* Trans between OMA, ER, OP */
		$('#bop').click(function(){
			var oma_Val = $.trim($('#oma').val());
			var er_Val = $.trim($('#er').val());
			if (oma_Val == '' || er_Val == '') var re = '';
			else {
				oma_Val = Number(oma_Val);
				er_Val = Number(er_Val);
				if (isNaN(oma_Val)||isNaN(er_Val)) var re = 'Err: Not a Number';
				else {
					oma_Val = Math.pow(10, (oma_Val/10));
					er_Val = Math.pow(10, (er_Val/10));
					var re = oma_Val*(er_Val + 1)/(2*(er_Val-1));
					re = 10*(Math.log(re)/Math.LN10);
					re = Math.round(100000000*re)/100000000;
				}
			}
			$('#op').val(re);
		});
				
		$('#boma').click(function(){
			var op_Val = $.trim($('#op').val());
			var er_Val = $.trim($('#er').val());
			if (op_Val == '' || er_Val == '') var re = '';
			else {
				op_Val = Number(op_Val);
				er_Val = Number(er_Val);
				if (isNaN(op_Val)||isNaN(er_Val)) var re = 'Err: Not a Number';
				else {
					op_Val = Math.pow(10, (op_Val/10));
					er_Val = Math.pow(10, (er_Val/10));
					var re = 2*(er_Val-1)*op_Val/(er_Val + 1);
					re = 10*(Math.log(re)/Math.LN10);
					re = Math.round(100000000*re)/100000000;
				}
			}
			$('#oma').val(re);
		});
				
				
		$('#ber').click(function(){
			var op_Val = $.trim($('#op').val());
			var oma_Val = $.trim($('#oma').val());
			if (op_Val == '' || oma_Val == '') var re = '';
			else {
				op_Val = Number(op_Val);
				oma_Val = Number(oma_Val);
				if (isNaN(op_Val)||isNaN(oma_Val)) var re = 'Err: Not a Number';
				else {
					op_Val = Math.pow(10, (op_Val/10));
					oma_Val = Math.pow(10, (oma_Val/10));
					var re = (2*op_Val+oma_Val)/(2*op_Val - oma_Val);
					re = 10*(Math.log(re)/Math.LN10);
					re = Math.round(100000000*re)/100000000;
				}
			}
			$('#er').val(re);
		});
	
		
	});
</script>
<?php get_footer();?>