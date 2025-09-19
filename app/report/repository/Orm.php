<?php
namespace report\repository;

interface Orm 
{
/*============================
Método para realizar un insert a 
cualquier tabla de la base de datos
===============================*/  

public function Insert(array $datos);

/********************** Query (inicializa la consulta)*************** */ 

public function query();

/********************** get => ejecuta toda las consultas *************** */ 

public function get();

/********************** first => ejecuta toda las consultas por where en particular*************** */ 

public function first();

/********************** select => selecciona las columnas o atributos de una tabla *************** */ 
public function select();
/**
 * Método es para realizar consultas al tener una tabla una condición where
 */
public function Where(string $Atributo,string $operador, $Valor);

/**
 * Método es para realizar consultas al tener una tabla una condición where con AND
 */
public function And(string $Atributo,string $operador, $Valor);


/**
 * Método es para realizar consultas al tener una tabla una condición where con OR
 */
public function Or(string $Atributo,string $operador, $Valor);

/**
 * Método join=> permite realizar consultas con tablas relacionados
 */
public function Join(string $TablaRelational,string $ColFk,string $operador,string $ColPk);
public function LeftJoin(string $TablaRelational,string $ColFk,string $operador,string $ColPk);

/**
 * Método OrdeBy => ordenar los registros de manera ascendente o descendente
 */

 public function orderBy(string $atributo,string $secuencia);

  /// Método Update

 public function Update(array $datos);

  /// Método delete
 
 public function delete($id);


 public function procedure(string $NameProcedure,$evento,array $datos=[]);

 public function GroupBy(array $grupo);

 public function InWhere($atributoConsultar,$Values = []);
 public function WhereIn($atributoConsultar,$Values = []);
 public function distinct();

 public function limit(int $limit);



 



}