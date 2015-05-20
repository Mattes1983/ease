if ( $_EASEF<ease:parentid /> )
{
    $_EASEF<ease:parentid />->fctAddField( array(
	"id"=>"<ease:id />",
	"type"=>"checkbox",
	"fieldname"=>"field<ease:id />",
	"name"=>"<ease:fieldname />",
	"required"=>"<ease:required />",
	"values_count"=>"<ease:valuescount />",
    ) );
}