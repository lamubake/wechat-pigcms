function g(o){return document.getElementById(o);}
function HoverLi(n){
//�����N����ǩ,�ͽ�i<=N;
for(var i=1;i<=5;i++){g('tb_'+i).className='normaltab';g('tbc_0'+i).className='undis';}g('tbc_0'+n).className='dis';g('tb_'+n).className='hovertab';
}
//���Ҫ���ɵ������ת���뽫<li>�е�onmouseover �ĳ� onclick;

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}