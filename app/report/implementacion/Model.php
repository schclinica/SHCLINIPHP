<?php 
namespace report\implementacion;
use config\BaseDatos;
use report\repository\Orm;

class Model extends BaseDatos implements Orm
{

  /* ----------------------------------- 
     PROPIEDADES PARA EL MODEL
    -----------------------------------*/

    /***  HACE REFERENCIA A LA TABLA DE UNA BASE DE DATOS* */
    protected string $Table;

    /*** HACE REFERENCIA A LA PK DE UNA TABLA DE LA BASE DE DATOS */

    protected string $PrimayKey;


    /***  HACE REFERENCIA AL ALIAS DE UNA TABLA*** */

    protected $Alias;

    /** Hace referencia al valor del atrubuto Where */

    private $Value;

    /** Hace referencia al valor del atrubuto Where con And y or, lo inicializamos en vacio */

    private $ValueWhereAndOr = [];




    /********************************  ACCIONES DEL MODELO ******************************************* */

    /********************** Query (inicializa la consulta)*************** */ 

    public function query()
    {
      $Tabla = $this->Table.$this->Alias;

      self::$Query = "SELECT * FROM $Tabla ";

      /// retornamos a la misma clase;

      return $this;
    }

     /** distinct */
 public function distinct(){
  self::$Query = str_replace("*","distinct * ",self::$Query);
  return $this;
 }

  /********************** all => ejecuta toda las consultas *************** */

  public function get()
  {
    
  try {
     self::$PPS = self::getConection()->prepare(self::$Query);

     if(!empty($this->Value))
     {
      self::$PPS->bindValue(1,$this->Value);
     }

     if(sizeof($this->ValueWhereAndOr) > 0 )
     {
      /// comenzamos desde 2

      for ($i=0; $i <count($this->ValueWhereAndOr) ; $i++) { 

        self::$PPS->bindValue($i+1,$this->ValueWhereAndOr[$i]);
      }
     }

 
     /// ejecutamos toda consulta
     self::$PPS->execute();


     return self::$PPS->fetchAll(\PDO::FETCH_OBJ);
  } catch (\Throwable $th) {  
      echo $th->getMessage();
  }finally{
    $this->ValueWhereAndOr=[];
    $this->Value = null;
    self::closeDataBase();}
  }

  /********************** first => ejecuta toda las consultas por where en particular*************** */ 

public function first()
{
  try {
    self::$PPS = self::getConection()->prepare(self::$Query);

    // if(!empty($this->Value))
    // {
    //  self::$PPS->bindValue(1,$this->Value);
    // }

    if(sizeof($this->ValueWhereAndOr) > 0 )
    {
     /// comenzamos desde 2

     for ($i=0; $i <count($this->ValueWhereAndOr) ; $i++) { 

       self::$PPS->bindValue($i+1,$this->ValueWhereAndOr[$i]);
     }
    }

    /// ejecutamos toda consulta
    self::$PPS->execute();


    if(self::$PPS->rowCount()>0)
    {
      return self::$PPS->fetchAll(strtoupper(\PDO::FETCH_OBJ))[0];
    }
    return [];
 } catch (\Throwable $th) {  
     echo $th->getMessage();
 }finally{
  $this->ValueWhereAndOr=[];
  $this->Value = null;
  self::closeDataBase();}
}


  /********************** select => selecciona las columnas o atributos de una tabla *************** */ 
 public function select()
 {
  $Atributos = func_get_args();///[name,apellidos,dni,direccion]

  $Atributos = implode(",",$Atributos);/// name,apellidos,dni,direccion

  self::$Query = str_replace("*",$Atributos,self::$Query);

  return $this;
 }

 /**
 * Método es para realizar consultas al tener una tabla una condición where
 */
public function Where(string $Atributo,string $operador,$Valor)
{
  if($Valor == null)
  {
  self::$Query.= " WHERE  $Atributo $operador null";
  }
  else
  {
    self::$Query.= " WHERE  $Atributo $operador ?";
    $this->ValueWhereAndOr[] = $Valor;
  }

  return $this;
}

/**
 * Método es para realizar consultas al tener una tabla una condición where con AND
 */
public function And(string $Atributo,string $operador, $Valor)
{
  self::$Query.= " AND $Atributo $operador ?";


  $this->ValueWhereAndOr[] = $Valor;

  return  $this;


}

/**
 * Método es para realizar consultas al tener una tabla una condición where con OR
 */
public function Or(string $Atributo,string $operador, $Valor)
{
  self::$Query.= " OR $Atributo $operador ?";


  $this->ValueWhereAndOr[] = $Valor;

  return  $this;


}

public function InWhere($atributeConsult,$values =[])
{

  $NuevoParametro = implode(",",$values);//

  self::$Query.=" AND $atributeConsult in(".$NuevoParametro.")";

  return $this;
}

public function WhereIn($atributeConsult,$values =[])
{

  $NuevoParametro = implode(",",$values);//

  self::$Query.=" WHERE $atributeConsult in(".$NuevoParametro.")";

  return $this;
}

/**
 * Método join=> permite realizar consultas con tablas relacionados
 */
public function Join(string $TablaRelational,string $ColFk,string $operador,string $ColPk)
{
  self::$Query.= " INNER JOIN $TablaRelational ON $ColFk $operador $ColPk";

  return $this;
}

/**
 * LEFT JOIN TABLAS RELACIONADAS
 */
public function LeftJoin(string $TablaRelational,string $ColFk,string $operador,string $ColPk)
{
  self::$Query.= " LEFT JOIN $TablaRelational ON $ColFk $operador $ColPk";

  return $this;
}

/**
 * Método OrdeBy => ordenar los registros de manera ascendente o descendente
 */

 public function orderBy(string $atributo,string $secuencia)
 {
  self::$Query.= " ORDER BY $atributo $secuencia";
  return $this;
 }

 public function GroupBy(array $grupo)
 {
  $grupo = implode(",",$grupo);
  self::$Query.=" group by ".$grupo;
  return $this;
 }

    
    
    /*============================
    Método para realizar un insert a 
    cualquier tabla de la base de datos
    insert into tabla(atr1,atr2)
    ===============================*/  

    public function Insert(array $datos)
    {
      self::$Query = "INSERT INTO $this->Table(";

       
      foreach($datos as $atribute=>$value):
        self::$Query.=$atribute.",";
      endforeach;
      
      self::$Query = rtrim(self::$Query,",").") VALUES(";

      foreach($datos as $atribute=>$value):
        self::$Query.=":$atribute,";
      endforeach;

      self::$Query = rtrim(self::$Query,",").")";
       
      try {
       self::$PPS = self::getConection()->prepare(self::$Query);

       foreach($datos as $atribute=>$value):
         self::$PPS->bindValue(":$atribute",$value);
      endforeach;

      return self::$PPS->execute();

      } catch (\Throwable $th) {
        echo $th->getMessage();
      }finally{self::closeDataBase();}

    }

    /// Método Update => UPDATE estudiante set nombres=:nombres,apellidos=:apellidos where id_estudiante=:id_estudiante

 public function Update(array $datos)
 {
   self::$Query = "UPDATE $this->Table SET ";

   /// le especificamos que atributos vamos a modificar

   foreach($datos as $atributo=>$value)
   {
      self::$Query.="$atributo=:$atributo,";
   }
   /// eliminamos la ultima coma

   self::$Query = rtrim(self::$Query,",")." WHERE ".array_key_first($datos)."=:".array_key_first($datos);

   /// el proceso de pdo para ejecutar dicha query

   try {
      self::$PPS = self::getConection()->prepare(self::$Query); 

      foreach ($datos as $key => $value) {
       self::$PPS->bindValue(":$key",$value);
      }
      
      return self::$PPS->execute(); /// 0 | 1

   } catch (\Throwable $th) {
      echo $th->getMessage();
   }finally{self::closeDataBase();}
 }

 /// Método delete => DELETE FROM TABLA WHERE id

 public function delete($id)
 {
   
   self::$Query = "DELETE FROM $this->Table WHERE $this->PrimayKey=:$this->PrimayKey";

    /// el proceso de pdo para ejecutar dicha query
    
    try {
      self::$PPS = self::getConection()->prepare(self::$Query); 

      self::$PPS->bindParam(":$this->PrimayKey",$id);
       
      return self::$PPS->execute(); /// 0 | 1

   } catch (\Throwable $th) {
      echo $th->getMessage();
   }finally{self::closeDataBase();}
 }

  /// procedimiento almacenado para realizar [CRUD COMPLETO]
  public function procedure(string $NameProcedure,$evento,array $datos=[])
  {
    self::$Query = "CALL $NameProcedure(";
 
    foreach($datos as $value)
    {
       self::$Query.="?,";
    }
 
    self::$Query = rtrim(self::$Query,",").")";
 
    try {
       self::$PPS = self::getConection()->prepare(self::$Query);
 
       for ($i=0; $i <count($datos) ; $i++) { 
          
          self::$PPS->bindValue(($i+1),$datos[$i]);
       }
       if(strtoupper($evento) ==='C')
       {
          self::$PPS->execute();
  
          return self::$PPS->fetchAll(\PDO::FETCH_OBJ);
       }
 
       return self::$PPS->execute();
 
    } catch (\Throwable $th) {
       echo $th->getMessage();
    }finally{self::closeDataBase();}
  }
    
  /** LIMIT */
  public function limit(int $limit)
  {
    self::$Query.=" LIMIT ".$limit;
    return $this;
  }
}