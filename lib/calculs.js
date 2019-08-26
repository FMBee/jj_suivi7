
// Changement des valeurs

$( "#taille" ).on( "blur", function() {
	y=$( "#pd_avt" ).val()/($( "#taille" ).val()*$( "#taille" ).val());
	z=$( "#pd_apr" ).val()/($( "#taille" ).val()*$( "#taille" ).val());
	$("#imc_avt").val(y);
	$("#imc_apr").val(z);
});

$( "#pd_avt" ).on( "blur", function() {
	r=$( "#pd_avt" ).val() - $( "#pd_apr" ).val();
	s=($( "#pd_apr" ).val() - $( "#pd_avt" ).val()) / $( "#pd_apr" ).val() * 100;
	t=(($( "#pd_avt" ).val()*$( "#grss_avt" ).val())-($( "#pd_apr" ).val()*$( "#grss_apr" ).val()))/100;
	w=($( "#mscl_kg_apr" ).val()/$( "#pd_apr" ).val()-$( "#mscl_kg_avt" ).val()/$( "#pd_avt" ).val())*100;
	y=$( "#pd_avt" ).val()/($( "#taille" ).val()*$( "#taille" ).val());
	$("#perte_pd_kg").val(r);
	$("#perte_pd_prc").val(s);
	$("#perte_grss").val(t);
	$("#gain_mscl").val(w);
	$("#imc_avt").val(y);
});

$( "#pd_apr" ).on( "blur", function() {
	r=$( "#pd_avt" ).val() - $( "#pd_apr" ).val();
	s=($( "#pd_apr" ).val() - $( "#pd_avt" ).val()) / $( "#pd_apr" ).val() * 100;
	t=(($( "#pd_avt" ).val()*$( "#grss_avt" ).val())-($( "#pd_apr" ).val()*$( "#grss_apr" ).val()))/100;
	w=($( "#mscl_kg_apr" ).val()/$( "#pd_apr" ).val()-$( "#mscl_kg_avt" ).val()/$( "#pd_avt" ).val())*100;
	z=$( "#pd_apr" ).val()/($( "#taille" ).val()*$( "#taille" ).val());
	$("#perte_pd_kg").val(r);
	$("#perte_pd_prc").val(s);
	$("#perte_grss").val(t);
	$("#gain_mscl").val(w);
	$("#imc_apr").val(z);
});

$( "#grss_avt" ).on( "blur", function() {
	t=(($( "#pd_avt" ).val()*$( "#grss_avt" ).val())-($( "#pd_apr" ).val()*$( "#grss_apr" ).val()))/100;
	$("#perte_grss").val(t);
});

$( "#grss_apr" ).on( "blur", function() {
	t=(($( "#pd_avt" ).val()*$( "#grss_avt" ).val())-($( "#pd_apr" ).val()*$( "#grss_apr" ).val()))/100;
	$("#perte_grss").val(t);
});

$( "#mscl_kg_avt" ).on( "blur", function() {
	u=$( "#mscl_kg_avt" ).val()/$( "#pd_avt" ).val()*100;
	w=($( "#mscl_kg_apr" ).val()/$( "#pd_apr" ).val()-$( "#mscl_kg_avt" ).val()/$( "#pd_avt" ).val())*100;
	$("#mscl_prc_avt").val(u);
	$("#gain_mscl").val(w);
});

$( "#mscl_kg_apr" ).on( "blur", function() {
	v=$( "#mscl_kg_apr" ).val()/$( "#pd_apr" ).val()*100;
	w=($( "#mscl_kg_apr" ).val()/$( "#pd_apr" ).val()-$( "#mscl_kg_avt" ).val()/$( "#pd_avt" ).val())*100;
	$("#mscl_prc_apr").val(v);
	$("#gain_mscl").val(w);
});

$( "#mss_os_avt" ).on( "blur", function() {
	x=($( "#mss_oss_apr" ).val()/$( "#pd_apr" ).val()-$( "#mss_oss_avt" ).val()/$( "#pd_avt" ).val())*100;
	$("#gain_os").val(x);
});

$( "#mss_os_apr" ).on( "blur", function() {
	x=($( "#mss_oss_apr" ).val()/$( "#pd_apr" ).val()-$( "#mss_oss_avt" ).val()/$( "#pd_avt" ).val())*100;
	$("#gain_os").val(x);
});
