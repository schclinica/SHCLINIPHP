<?php 
namespace report\repository;
use report\implementacion\Model;
abstract class CrudRepository extends Model
{
/*============================
Realiza un nuevo registro
===============================*/

public abstract static function create(array $datos);

/*============================
Realiza la modificación de registros
===============================*/
public abstract static function update(array $datos);

/*============================
Elimina registros
===============================*/

public abstract static function delete($id);
/*============================
mostrar registros
===============================*/

public abstract static function all();

/*============================
MOSTRAR POR ID
===============================*/
public abstract static function getById($id);

}