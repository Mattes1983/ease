if ( $_EASEF<ease:parentid /> )
{
    $_EASEF<ease:parentid />->fctAddField( array(
	"id"=>"<ease:id />",
	"type"=>"select",
	"fieldname"=>"field<ease:id />",
	"name"=>"<ease:fieldname />",
	"required"=>"<ease:required />"
    ) );
}